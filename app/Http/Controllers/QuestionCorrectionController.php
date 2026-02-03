<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class QuestionCorrectionController extends Controller
{
    // Credentials pulled from your ChatbotController
    private const DS_TOKEN = "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";
    private const API_URL = 'https://openrouter.ai/api/v1/chat/completions';
    private const LLM_MODEL = 'x-ai/grok-4.1-fast';
    
    // Define the valid question categories
    private const QUESTION_TYPES = ['CQ', 'MCQ', 'Writing'];

    public function index()
    {
        $question = Post::whereIn('category', self::QUESTION_TYPES) // Restrict category
            ->where('is_verified', false)
            ->where('institution_id', 4)
            ->whereIn('subject_id', [86, 87])
            ->first();

        if (!$question) return "All questions verified!";
    
        return view('admin.verify-questions', compact('question'));
    }
    
    public function getAiSuggestion(Post $post)
    {
        // Safety check to ensure we aren't sending non-question data to the AI
        if (!in_array($post->category, self::QUESTION_TYPES)) {
            return response()->json(['error' => 'Not a valid question type'], 400);
        }

        $prompt = "Fix typos in this JSON: {\"article\":\"{$post->article}\", \"a\":\"{$post->a}\", \"b\":\"{$post->b}\", \"c\":\"{$post->c}\", \"d\":\"{$post->d}\", \"ans\":\"{$post->ans}\", \"explanation\":\"{$post->explanation}\"}. Return ONLY raw JSON.";

        $response = Http::withHeaders([
            'Authorization' => "Bearer " . self::DS_TOKEN,
            'Content-Type'  => 'application/json',
        ])->post(self::API_URL, [
            "model" => self::LLM_MODEL,
            "messages" => [
                ["role" => "user", "content" => $prompt]
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? '';
            $cleanJson = preg_replace('/^```json|```$/m', '', $text);
            return response()->json(json_decode(trim($cleanJson), true));
        }

        return response()->json(['error' => 'AI Suggestion failed'], 500);
    }

    public function update(Request $request, Post $post)
    {
        if ($request->action === 'update') {
            $post->update($request->except(['_token', 'action']) + ['is_verified' => true]);
        } else {
            $post->update(['is_verified' => true]); 
        }

        return redirect()->back()->with('success', 'Database updated!');
    }

    public function bulkFixTypos()
    {
        $wrong = "	imes"; 
        $correct = "\times ";
    
        $posts = Post::whereIn('category', self::QUESTION_TYPES) // Restrict category
                    ->where(function($query) use ($wrong) {
                        $query->where('article', 'LIKE', "%$wrong%")
                            ->orWhere('a', 'LIKE', "%$wrong%")
                            ->orWhere('b', 'LIKE', "%$wrong%")
                            ->orWhere('c', 'LIKE', "%$wrong%")
                            ->orWhere('d', 'LIKE', "%$wrong%")
                            ->orWhere('explanation', 'LIKE', "%$wrong%");
                    })
                    ->get();
    
        $count = 0;
        foreach ($posts as $post) {
            $post->update([
                'article'     => str_replace($wrong, $correct, $post->article),
                'a'           => str_replace($wrong, $correct, $post->a),
                'b'           => str_replace($wrong, $correct, $post->b),
                'c'           => str_replace($wrong, $correct, $post->c),
                'd'           => str_replace($wrong, $correct, $post->d),
                'explanation' => str_replace($wrong, $correct, $post->explanation),
            ]);
            $count++;
        }
    
        return "Fixed $count questions! Please delete this route now.";
    }
    
    /**
     * Automatically find a post with a null topic_name and populate it using OpenRouter (Grok/DeepSeek).
     */
    public function autoPopulateTopic()
    {
        $post = Post::whereIn('category', self::QUESTION_TYPES) // Restrict category
                ->where(function($query) {
                    $query->whereNull('topic_name')
                          ->orWhere('topic_name', '');
                })
                ->where('institution_id', 4)
                ->with('subject') 
                ->first();
    
        if (!$post) return "Done! All topics populated.";
    
        $subjectName = $post->subject ? $post->subject->name : 'General Knowledge';
    
        $prompt = "You are an exam syllabus expert.

Task:
Identify the most specific and accurate TOPIC name for the following question.

Rules:
- Return ONLY the topic name
- 1–3 words only
- NO explanation, NO punctuation, NO extra text
- If the question is in Bengali, return topic in Bengali
- If the question is in English, return topic in English
- Use a standard syllabus-level topic name (neither too broad nor too specific)

Subject: {$subjectName}

Question:
{$post->article}

Options:
A) {$post->a}
B) {$post->b}
C) {$post->c}
D) {$post->d}

Correct Answer:
{$post->ans}";
    
        try {
            $response = Http::timeout(120)->withHeaders([
                'Authorization' => "Bearer " . self::DS_TOKEN,
                'Content-Type'  => 'application/json',
                'HTTP-Referer'  => 'http://localhost', 
            ])->post(self::API_URL, [
                "model" => self::LLM_MODEL,
                "messages" => [
                    ["role" => "system", "content" => "Bengali educational expert. Provide topic names only."],
                    ["role" => "user", "content" => $prompt]
                ],
                "stream" => false, 
                "max_tokens" => 50,
            ]);
    
            if ($response->status() === 429) {
                return response("Rate limit. <script>setTimeout(() => { location.reload(); }, 30000);</script> Waiting...", 429);
            }
    
            if ($response->successful()) {
                $resData = $response->json();
                $suggestedTopic = $resData['choices'][0]['message']['content'] ?? null;
    
                if ($suggestedTopic) {
                    $cleanTopic = trim(str_replace(['"', "'", '*', '#'], '', $suggestedTopic));
                    $post->update(['topic_name' => Str::limit($cleanTopic, 100)]);
                    
                    return "Updated ID: {$post->id} to <b>{$cleanTopic}</b>. <script>setTimeout(() => { location.reload(); }, 1500);</script>";
                }
            }
    
            return "Error: " . $response->status() . " - " . $response->reason();
    
        } catch (\Exception $e) {
            return "Timeout/Exception. <script>setTimeout(() => { location.reload(); }, 5000);</script> Retrying...";
        }
    }
}