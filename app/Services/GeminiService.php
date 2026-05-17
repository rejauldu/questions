<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Board;
use App\Models\Subject;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey, $mcqKey;
    protected static $keyIndex = 0;
    
    // ফ্রি টায়ার মডেল লিস্ট
    protected $models = [
        'gemini-2.5-flash',
    ];
    
    const DEFAULT_YEAR = 2025;
    const API_KEYS = [
        "AIzaSyBkIz2kGHsPK_S8i3Te2rm3CCCeNLA2bcQ",
        // "AIzaSyBHLo8PM-Fqc0NhB6JqpX5Eu3c9cSfO3sE",
        // "AIzaSyAD0t1ZuRsHbNxJLW4KbZebii7Az80UKSc",
    ];

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->mcqKey = "AIzaSyBHLo8PM-Fqc0NhB6JqpX5Eu3c9cSfO3sE";
    }
    
    // নতুন মেথড: Sequential কি পাওয়ার জন্য
    protected function getNextApiKey()
    {
        $keys = self::API_KEYS;
        $key = $keys[self::$keyIndex];
        
        // ইনডেক্স আপডেট করা (পরের বার পরের কি পাবে)
        self::$keyIndex = (self::$keyIndex + 1) % count($keys);
        
        return $key;
    }
    
    // Group 1: Fill missing topics

    public function fillMissingTopics()
    {
        // ১. ১টি পোস্ট রিট্রিভ করা যোর topic_name বা chapter নেই
        $firstPost = Post::where(function ($query) {
                $query->whereNull('chapter')->orWhere('chapter', '');
            })
            //->where('subject_id', 6)
            ->first();
        

        // যদি কোনো পোস্টই খালি না থাকে
        if (!$firstPost) return null;

        // ২. সাবজেক্ট এবং চ্যাপ্টার লিস্ট বের করা (প্রথম পোস্টের সাবজেক্ট আইডি থেকে)
        $subject = Subject::find($firstPost->subject_id);
        if (!$subject) return null;
        
        // ১. ১০টি পোস্ট রিট্রিভ করা যেগুল বা chapter নেই
        $posts = Post::where(function ($query) {
                $query->whereNull('chapter')->orWhere('chapter', '');
            })
            ->where('subject_id', $subject->id)
            ->limit(20)
            ->get();

        // ৩. জেমিনি থেকে ডেটা প্রসেস করা
        $results = $this->tryAllModels($posts, $subject);

        if ($results && is_array($results)) {
            foreach ($results as $item) {
                $p = Post::find($item['id']);
                if ($p) {
                    $p->update([
                        'topic_name' => $item['topic_name'] ?? '',
                        'chapter' => $item['chapter'] ?? null
                    ]);
                }
            }
            return $results;
        }

        return null;
    }

    protected function tryAllModels($posts, $subject)
    {
        foreach ($this->models as $modelName) {
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent";

            $result = $this->askGeminiBatch($posts, $subject, $apiUrl);

            if ($result !== null) {
                return $result; 
            }
        }
        return null;
    }

    protected function askGeminiBatch($posts, $subject, $apiUrl)
    {
        try {
            // চ্যাপ্টার লিস্ট স্ট্রিং হিসেবে তৈরি
            $chapterList = is_array($subject->chapters) ? json_encode($subject->chapters, JSON_UNESCAPED_UNICODE) : $subject->chapters;

            // ১০টি রো ডেটা প্রম্পটের জন্য সাজানো
            $rows = $posts->map(function($p) {
                return [
                    'id' => $p->id,
                    'article' => $p->article,
                    'options' => "A:{$p->a}, B:{$p->b}, C:{$p->c}, D:{$p->d}"
                ];
            })->toJson(JSON_UNESCAPED_UNICODE);
            
            $prompt = "You are an expert academic evaluator for HSC, BCS, and Departmental exams. 

            Context:
            - Use the 'Chapter List' as the ONLY source for determining the 'chapter' value.
            - Each item in the 'Chapter List' starts with a number followed by a dot (e.g., '1. Chapter Name'). Your task is to extract ONLY that number.
            
            Chapter List (JSON): {$chapterList}
            Input Data (JSON): {$rows}
            
            CRITICAL RULES:
            1. Return EXACTLY " . $posts->count() . " objects. Each object MUST correspond to an ID from the Input Data.
            2. 'id': Must be the EXACT integer ID provided in Input Data.
            3. 'topic_name': 1-6 words specific to the question (match article's language). If article is in Bengali then topic name will be in Bengali, otherwise english.
            4. 'chapter': This MUST be the numeric part (e.g., if the list has '5. Vector', return 5). If multiple chapters seem relevant, pick the best one. Never return null.
            5. Strict JSON: No markdown code blocks (```json), no intro, no trailing commas.
            
            Output Format (Here, id and chapter are just example to show. You will decide from chapter list):
            [{\"id\": 1, \"topic_name\": \"...\", \"chapter\": 5}, ...]";

            $response = Http::timeout(60)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$apiUrl}?key={$this->apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => [
                        'temperature' => 0.1,
                        'response_mime_type' => 'application/json', // সরাসরি JSON আউটপুট নিশ্চিত করে
                    ]
                ]);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');
                return json_decode($text, true);
            }

            if ($response->status() === 429) return null;

        } catch (\Exception $e) {
            Log::error("Gemini Service Exception: " . $e->getMessage());
        }
        return null;
    }
    
    // Group 4: MCQ answer and explanation
    
    public function fillMissingAnsAndExplanations()
    {
        // 1. Get the first MCQ where 'ans' is empty
        $firstPost = Post::where('category', 'MCQ') // Explicitly targeting MCQs
            ->where(function ($query) {
                $query->whereNull('ans')->orWhere('ans', '');
            })
            ->first();
    
        if (!$firstPost) return null;
    
        $subject = Subject::find($firstPost->subject_id);
        if (!$subject) return null;
    
        // 2. Fetch up to 20 posts for that specific subject
        $posts = Post::where('subject_id', $subject->id)
            ->where('category', 'MCQ')
            ->where(function ($query) {
                $query->whereNull('ans')->orWhere('ans', '');
            })
            ->limit(20)
            ->get();
    
        // 3. Process through AI models
        $results = $this->trySolveMCQ($posts, $subject);
    
        if ($results && is_array($results)) {
            foreach ($results as $item) {
                $p = Post::find($item['id']);
                if ($p) {
                    $p->update([
                        'ans' => $item['ans'] ?? '',
                        'explanation' => $item['explanation'] ?? null
                    ]);
                }
            }
            return $results;
        }
    
        return null;
    }
    
    protected function trySolveMCQ($posts, $subject)
    {
        foreach ($this->models as $modelName) {
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent";
            $result = $this->askGeminiForAnswers($posts, $subject, $apiUrl);
    
            if ($result !== null) return $result;
        }
        return null;
    }
    
    protected function askGeminiForAnswers($posts, $subject, $apiUrl)
    {
        try {
            $rows = $posts->map(function($p) {
                return [
                    'id' => $p->id,
                    'article' => $p->article,
                    'options' => "A:{$p->a}, B:{$p->b}, C:{$p->c}, D:{$p->d}"
                ];
            })->toJson(JSON_UNESCAPED_UNICODE);
            
            $prompt = "You are an expert academic examiner for Bangladeshi curriculum (HSC, SSC, BCS).
            
            Subject: {$subject->name}
            Input Data (JSON): {$rows}
            
            Your task:
            1. Identify the correct answer (a, b, c, or d).
            2. Provide a concise explanation (1-10 lines) in the same language as the question (Bengali or English).
            
            CRITICAL RULES:
            1. Return exactly " . $posts->count() . " objects matching the provided IDs.
            2. 'ans': Must be exactly one character: 'a', 'b', 'c', or 'd'.
            3. 'explanation': Provide a clear logical reason. If it's a math/science problem, briefly show the step. Keep it under 10 lines.
            4. Strict JSON output: No markdown, no intro.
            
            Format:
            [{\"id\": 1, \"ans\": \"a\", \"explanation\": \"...\"}, ...]";
    
            $response = Http::timeout(90) // Slightly longer timeout for complex explanations
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$apiUrl}?key={$this->mcqKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => [
                        'temperature' => 0.1,
                        'response_mime_type' => 'application/json',
                    ]
                ]);
    
            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');
                return json_decode($text, true);
            }
    
            if ($response->status() === 429) return null;
    
        } catch (\Exception $e) {
            Log::error("Gemini MCQ Service Exception: " . $e->getMessage());
        }
        return null;
    }
    
    // Group 3: OCR

    /**
     * OCR Entry Point: Tries models one by one until one works.
     */
    public function askGeminiWithImage($meta)
    {
        
        foreach ($this->models as $modelName) {
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent";
            
            $result = $this->executeOcrRequest($meta, $apiUrl);

            if ($result !== null) {
                return $result; 
            }
            
            Log::warning("Model {$modelName} failed or throttled. Trying next...");
        }
        return null;
    }

    /**
     * The actual API call logic with metadata injection and conditional board handling
     */
    protected function executeOcrRequest($meta, $apiUrl)
    {
        $currentKey = $this->getNextApiKey();
        $year = $meta['year'] ?? self::DEFAULT_YEAR;
        $fullPath = $meta['filePath'];
    
        try {
            if (!file_exists($fullPath)) return null;
    
            // 1. Fetch lookup data
            $subject = Subject::where('slug', $meta['subject_slug'])->first(['id', 'name', 'chapters']);
            
            $board = Board::where('name', 'LIKE', "%{$meta['board_name']}%")->first(['id', 'name']);
            
            $chapters = is_array($subject->chapters) ? json_encode($subject->chapters) : ($subject->chapters ?? 'General');

            $subjectContext = $subject 
                ? "Subject: {$subject->name} (ID: {$subject->id}). Chapters: {$chapters}"
                : "Subject unknown.";
            
            $boardContext = $board 
                ? "Board: {$board->name} (ID: {$board->id})" 
                : "Board: NULL. If this is an HSC paper, analyze the image header/text to identify the board. If you find the board, you may use it; otherwise, set 'board_id' to null. If not an HSC paper, set 'board_id' to null.";
            
            $imageData = base64_encode(file_get_contents($fullPath));
            $mimeType = mime_content_type($fullPath);
    
            // 3. Optimized Prompt with Precise Field Mapping
            $prompt = "You are an expert academic OCR evaluator. 
CONTEXT: {$subjectContext}. {$boardContext}. Year: {$year}.
TASK: Extract all questions from the image into a JSON array.
KEYS REQUIRED: ['article', 'a', 'b', 'c', 'd', 'category', 'ans', 'q_no', 'has_complex_html', 'topic_name', 'chapter'].
FIELD MAPPING RULES:
1. 'article': EXTRACT ONLY the stimulus or the question stem for MCQ. YOU MUST EXCLUDE any text representing options (e.g., 'ক', 'খ', 'গ', 'ঘ' or their corresponding content).
2. 'a', 'b', 'c', 'd': EXTRACT ONLY the content corresponding to 'ক', 'খ', 'গ', 'ঘ' respectively. Do not include the markers 'ক', 'খ', 'গ', 'ঘ' themselves in the value.
3. 'ans': Provide only the correct option letter for MCQ. For CQ, null.
4. 'q_no': The question sequence number found on the paper.
5. 'topic_name': Based on the chapter context provided. 1 to 5 words.
6. 'chapter': The numeric index of the chapter.
7. 'category': Identify as 'MCQ' or 'CQ'.
8. 'has_complex_html': 1 if diagram or table present, else 0.
FORMATTING RULES:
- Math: Wrap in '$'. Use double backslashes for LaTeX.
- MCQ Stimulus: Normally single question has single stimulus but sometimes two question can have a single stimulus. In that case prepend shared stimulus to the 'article' of both these two MCQs.
Return ONLY valid JSON.";
    
            // 4. API Request
            $response = Http::timeout(120)->post("{$apiUrl}?key={$currentKey}", [
                'contents' => [
                    ['parts' => [
                        ['text' => $prompt],
                        ['inline_data' => ['mime_type' => $mimeType, 'data' => $imageData]]
                    ]]
                ],
                'generationConfig' => [
                    'temperature' => 0.05,
                    'response_mime_type' => 'application/json',
                ]
            ]);
    
            if ($response->successful()) {
                $data = json_decode($response->body(), true);
                
                Log::error("Data received: " . $response->body());
                
                if (is_array($data)) {
                    // Normalize: If it's a single object (associative), wrap it in an array
                    $items = (isset($data['article'])) ? [$data] : $data;
            
                    return array_map(function ($item) use ($subject, $board, $year) {
                        // Ensure $item is an array before setting keys
                        if (is_array($item)) {
                            $item['subject_id'] = $subject->id ?? null;
                            $item['board_id']   = $board->id ?? null;
                            $item['year']       = $year;
                            $item['user_id']    = 1;
                            $item['is_verified'] = 1;
                        }
                        return $item;
                    }, $items);
                }
            }
    
            Log::error("OCR API Error: " . $response->body());
            return null;
    
        } catch (\Exception $e) {
            Log::error("Image OCR Exception: " . $e->getMessage());
            return null;
        }
    }
}