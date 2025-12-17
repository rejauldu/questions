<?php

namespace App\Http\Controllers;

use App\Models\ChatThread;
use App\Models\Message;
use App\Models\Institution;
use App\Models\Subject;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Routing\Controller;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    private const DS_TOKEN = "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"; // Truncated example
    private const API_URL = 'https://openrouter.ai/api/v1/chat/completions';
    private const LLM_MODEL = 'x-ai/grok-4.1-fast';

    /**
     * Renders the Chatbot page
     */
    public function chatbot(?string $id = null)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }

        // If no thread ID is provided → check for existing pending thread
        if (!$id) {
            $thread = ChatThread::where('user_id', $userId)
                ->where('is_pending', true)
                ->first();

            // If no pending thread → create a new one
            if (!$thread) {
                $thread = ChatThread::create([
                    'id'         => Str::ulid(),
                    'user_id'    => $userId,
                    'title'      => 'New Chat',
                    'is_pending' => true,
                ]);
            }

            return redirect()->route('chatbot', ['id' => $thread->id]);
        }

        // Fetch user threads (limit 16)
        $threads = ChatThread::where('user_id', $userId)
            ->select('id', 'title')
            ->latest()
            ->limit(16)
            ->get();

        // Load existing thread
        $thread = ChatThread::where('id', $id)
            ->where('user_id', $userId)
            ->with(['messages' => fn($q) => $q->orderBy('created_at')])
            ->firstOrFail();

        // Build last 10 messages
        $messages = $thread->messages->slice(-10)->map(fn($msg) => [
            'id'     => $msg->id,
            'text'   => $msg->content,
            'sender' => $msg->sender_type,
        ])->toArray();

        $activeChat = [
            'id'       => $thread->id,
            'title'    => $thread->title,
            'messages' => $messages,
        ];

        return Inertia::render('Chatbot/Index', [
            'threads'    => $threads->toArray(),
            'activeChat' => $activeChat,
        ]);
    }



    /**
     * Handles user message + bot reply
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'nullable|string|exists:chat_threads,id',
            'message' => 'required|string|max:2000',
        ]);

        $userId = Auth::id();
        $userMessage = $request->input('message');
        $threadId = $request->input('chat_id');

        return DB::transaction(function () use ($userId, $userMessage, $threadId) {

            /**
             * 1. Load thread (always exists because UI creates pending thread)
             */
            $thread = ChatThread::where('id', $threadId)
                ->where('user_id', $userId)
                ->firstOrFail();

            $isNewThread = $thread->is_pending;


            /**
             * 2. If this is the first real message → update title + remove pending state
             */
            if ($isNewThread) {
                $thread->update([
                    'title' => substr($userMessage, 0, 50) . (strlen($userMessage) > 50 ? '...' : ''),
                    'is_pending' => false,
                ]);
            }


            /**
             * 3. Save user message
             */
            $userMessageRecord = $thread->messages()->create([
                'content' => Str::limit($userMessage, 200),
                'sender_type' => 'user',
            ]);


            /**
             * 4. Build short history (last 10 messages)
             */
            $history = $thread->messages()
                ->orderByDesc('created_at')
                ->take(10)
                ->get()
                ->reverse()
                ->map(fn($msg) => [
                    'role' => $msg->sender_type === 'user' ? 'user' : 'assistant',
                    'content' => $msg->content,
                ])
                ->toArray();


            /**
             * 5. If new thread: prepend initial assistant message
             */
            if ($isNewThread) {
                $initialBotMsg = [
                    'role' => 'assistant',
                    'content' => "Hello! I'm your Exam Date Assistant. Please tell me the course name or subject you need information for.",
                ];

                if (count($history)) {
                    array_pop($history);
                }

                $history = [$initialBotMsg, ...$history];
            }


            /**
             * 6. FAQ Lookup
             */
            $canonical = $this->canonicalize($userMessage);

            $faq = Faq::where('question', $canonical)->first();

            if ($faq) {

                $faq->increment('frequency');

                $botResponseText = $faq->answer;

            } else {

                /**
                 * 7. Call LLM
                 */
                $rawResponse = $this->callDeepseekAPI($userMessage, $history, $userId);

                $parsed = $this->decodeModelJson($rawResponse);


                if ($parsed && ($parsed['action'] ?? '') === 'faq') {

                    $canonicalQ = $this->canonicalize(
                        $parsed['canonical_question'] ?? $userMessage
                    );

                    $existing = Faq::where('question', $canonicalQ)->first();

                    if (!$existing) {
                        Faq::create([
                            'question'  => Str::limit($canonicalQ, 200),
                            'answer'    => Str::limit($parsed['response'], 200),
                            'frequency' => 1,
                        ]);
                    }

                    $botResponseText = $parsed['response'];

                } else {
                    $botResponseText = $parsed['response'] ?? $rawResponse;
                }
            }


            /**
             * 8. Save bot message
             */
            $botMessageRecord = $thread->messages()->create([
                'content' => Str::limit($botResponseText, 200),
                'sender_type' => 'bot',
            ]);

            $thread->touch();


            /**
             * 9. Return response
             */
            return response()->json([
                'new_thread_data' => $isNewThread ? [
                    'id' => $thread->id,
                    'title' => $thread->title,
                ] : null,
                'new_messages' => [
                    [
                        'id' => $userMessageRecord->id,
                        'text' => $userMessageRecord->content,
                        'sender' => 'user',
                    ],
                    [
                        'id' => $botMessageRecord->id,
                        'text' => $botMessageRecord->content,
                        'sender' => 'bot',
                    ]
                ],
            ]);
        });
    }



    /**
     * Call LLM using DB-based dynamic data
     */
    private function callDeepseekAPI(string $userQuery, array $chatHistory, int $userId): string
    {
        /**
         * ─────────────────────────────────────────────
         * 1. Load INSTITUTIONS from DB
         * ─────────────────────────────────────────────
         */
        $institutionList = Institution::select('id', 'name')->get();

        $INSTITUTIONS = $institutionList
            ->map(fn($i) => ['name' => $i->name])
            ->toArray();


        /**
         * ─────────────────────────────────────────────
         * 2. Load EXAM ROUTINES from DB (Subjects table)
         * ─────────────────────────────────────────────
         */
        $subjects = Subject::with('institution')
            ->where('status', 1) // <-- Only active subjects
            ->select('id', 'institution_id', 'name', 'exam_at', 'description')
            ->get();

        $EXAM_ROUTINES = [];
        foreach ($subjects as $subject) {
            $inst = $subject->institution->name;
            if (!isset($EXAM_ROUTINES[$inst])) {
                $EXAM_ROUTINES[$inst] = [];
            }
            $EXAM_ROUTINES[$inst][$subject->name] = $subject->exam_at
                ? Carbon::parse($subject->exam_at)->format('d-m-Y h:i A')
                : ($subject->description ?? "Date not published yet");
        }


        /**
         * ─────────────────────────────────────────────
         * 3. Convert routines to text (same format as before)
         * ─────────────────────────────────────────────
         */
        $routineText = "Available Exam Routines:\n\n";

        foreach ($EXAM_ROUTINES as $inst => $subs) {
            $routineText .= "Institution: $inst\n";
            $routineText .= "Subject      | Exam Date\n";
            $routineText .= "-------------------------\n";

            foreach ($subs as $sub => $date) {
                $routineText .= str_pad($sub, 12) . " | $date\n";
            }
            $routineText .= "\n";
        }


        /**
         * 4. Detect institute from user query (DB name slash-separated aliases)
         */
        $detectedInstitute = null;
        $lowerQuery = strtolower($userQuery);

        foreach ($INSTITUTIONS as $inst) {

            // Convert “Dhaka University/DU” → ["dhaka university", "du"]
            $aliasList = array_map(
                fn($a) => trim(strtolower($a)),
                explode('/', strtolower($inst['name']))
            );

            foreach ($aliasList as $alias) {
                if ($alias !== '' && str_contains($lowerQuery, $alias)) {
                    $detectedInstitute = $inst['name']; // store full institution name
                    break 2;
                }
            }
        }

        /**
         * ─────────────────────────────────────────────
         * 5. Use user's default institution (DB stored)
         * ─────────────────────────────────────────────
         */
        $userDefaultInstitute = auth()->user()->institution->name ?? null;

        if (!$detectedInstitute) {
            $detectedInstitute = $userDefaultInstitute;
        }

        if (!$detectedInstitute) {
            $detectedInstitute = $institutionList->first()->name ?? 'Unknown';
        }


        /**
         * ─────────────────────────────────────────────
         * 6. Find subject description from DB
         * ─────────────────────────────────────────────
         */
        
        $description = 'This is made by Rejaul Karim';


        /**
         * ─────────────────────────────────────────────
         * 7. Build system prompt (unchanged)
         * ─────────────────────────────────────────────
         */
        $today = Carbon::today()->format('d M Y');
        $currentMonth = Carbon::now()->format('F');
        $currentYear = Carbon::now()->year;

        $dateContext = "Today is $today. If the user mentions only a day, assume month $currentMonth and year $currentYear.";

        $systemContent = $this->systemText();


        /**
         * ─────────────────────────────────────────────
         * 8. Prepare messages for LLM
         * ─────────────────────────────────────────────
         */
        $messages = [
            ["role" => "system", "content" => $systemContent],
            ["role" => "assistant", "content" => $routineText],
            [
                "role" => "user",
                "content" => "If unclear, use this institution: $detectedInstitute. $dateContext. $description"
            ],
            ...$chatHistory,
            [
                "role" => "user",
                "content" => "If unclear, use this institution: $detectedInstitute. $dateContext. $description"
            ],
            [
                "role" => "user",
                "content" => $userQuery
            ],
        ];

        /**
         * ─────────────────────────────────────────────
         * 9. Call LLM API (unchanged)
         * ─────────────────────────────────────────────
         */
        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => "Bearer " . self::DS_TOKEN,
                'Content-Type'  => 'application/json',
            ])->post(self::API_URL, [
                "model" => self::LLM_MODEL,
                "messages" => $messages,
                "max_tokens" => 600,
            ]);

            $raw = $response->json();

            if ($response->successful() && !empty($raw['choices'][0]['message']['content'])) {
                return $raw['choices'][0]['message']['content'];
            }

            if (isset($raw['error'])) {
                return "API Error: " . substr(json_encode($raw['error']), 0, 100);
            }

            return "Unexpected server error.";

        } catch (\Throwable $e) {
            \Log::error("Deepseek API error: {$e->getMessage()}");
            return "Unable to fetch exam schedule due to a connectivity issue. Try again shortly.";
        }
    }

    private function canonicalize(string $text)
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }
    private function decodeModelJson(string $text)
    {
        if (preg_match('/```json(.*?)```/s', $text, $match)) {
            $text = trim($match[1]);
        }

        $decoded = json_decode($text, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }
    private function systemText(): string
{
    return <<<'EOT'
You are an intelligent exam date assistant. Follow these rules strictly and consistently:

────────────────────────────────────────
1. Exam Routine Questions (Highest Priority)
────────────────────────────────────────
A. Use ONLY the provided exam routine data to answer.
B. Determine institution/subject using this priority:
   1) The question itself
   2) The conversation context
   3) The user's default institution
C. If the chosen institution does not contain the requested subject or date:
   - Ask the user politely which institution they mean.
D. When a specific date is requested:
   - List ALL exams scheduled on that date.
   - Do NOT skip any subject.
   - If no exam exists: respond naturally (e.g., “No, there is no exam on that day.”)
E. Always answer in English.
F. Never include URLs in exam-routine responses.
G. Show all times in 12-hour format with AM/PM if needed.
H. Recognize institution aliases (e.g., “SSC / Secondary School Certificate”) and always use only the first alias.
I. Institution-Level Queries:
   - If the user asks only for an institution (e.g., "HSC exam"):
     1) Respond with the exam date of the first subject listed for that institution.
     2) If the first subject’s date has passed:
        - Mention it has passed.
        - Optionally provide the next year’s expected exam date around the same day/month.
     3) Do NOT list all subjects unless specifically requested.
     4) Always follow the output JSON structure.

────────────────────────────────────────
2. Date-Based Logic
────────────────────────────────────────
Handle relative dates (e.g., “tomorrow”, “next Monday”) and specific dates correctly.
Use the current system date unless a different reference date is provided.

────────────────────────────────────────
3. Non-Routine Academic Questions
────────────────────────────────────────
A. If the question is academic but NOT about exam routines:
   - If you know the answer → answer normally AND include this URL:
     https://examdao.com/search?q={slug}
   - If unknown → reply politely and include the same URL.
B. {slug} is a URL-friendly version of the user’s question:
   - lowercase, remove punctuation, replace spaces with hyphens

────────────────────────────────────────
4. Suggestions or Study Guidance
────────────────────────────────────────
For suggestions or advice (NOT exam routine answers):
- Include a friendly suggestion URL in this format:
  https://examdao.com/blog/institution-name/subject-name/year/board-name
- Include only known parameters in order: institution → subject → year → board
- Do NOT include these URLs in exam-routine answers.

────────────────────────────────────────
5. Other Questions
────────────────────────────────────────
Answer naturally. Include URLs only when required by above rules.

────────────────────────────────────────
6. Output Format (Very Strict)
────────────────────────────────────────
Every response MUST follow one of these JSON formats:

FAQ-type questions (repeated/static):
{
  "action": "faq",
  "response": "string",
  "canonical_question": "string"
}

Contextual / dynamic questions:
{
  "action": "contextual",
  "response": "string"
}

────────────────────────────────────────
7. Classification Rules
────────────────────────────────────────
- "faq": fixed/reusable answer (greetings, static info)
- "contextual": dynamic answer (exam schedules, user-specific)
- Always respond in JSON format as above.
- For "faq", ensure "canonical_question" is cleaned and normalized.
EOT;
}

}