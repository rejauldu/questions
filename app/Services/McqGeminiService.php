<?php
namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class McqGeminiService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        // Store your key in .env as GEMINI_API_KEY
        $this->apiKey = "AIzaSyBHLo8PM-Fqc0NhB6JqpX5Eu3c9cSfO3sE";
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";
    }

    public function processBatch()
    {
        // 1. Find the trigger row (MCQ, no answer)
        $trigger = Post::where('category', 'MCQ')
            ->where(function($query) {
                $query->whereNull('ans')->orWhere('ans', '');
            })
            ->first();

        if (!$trigger) {
            return "No pending MCQs found.";
        }

        // 2. Fetch the batch of 20 for the same subject
        $batch = Post::where('subject_id', $trigger->subject_id)
            ->where('category', 'MCQ')
            ->where(function($query) {
                $query->whereNull('explanation')->orWhere('explanation', '');
            })
            ->limit(5)
            ->get(['id', 'article', 'a', 'b', 'c', 'd']);

        // 3. Prepare the Prompt
        $instruction = "You are an educational assistant. I will provide a list of MCQs in JSON format. 
        For each 'id', provide the correct 'ans' (must be 'a', 'b', 'c', or 'd') and a short Bengali 'explanation' (around 2-10 sentences) using $ for Latex. 
        Return ONLY a raw JSON array of objects. To not truncate the response.
        Example Format: [{\"id\": 1, \"ans\": \"a\", \"explanation\": \"...\"}]";

        $prompt = $instruction . "\n\nData: " . $batch->toJson();

        // 4. Call Gemini API
        $response = Http::timeout(90)
            ->connectTimeout(10)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}?key={$this->apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                ]
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $rawJson = $data['candidates'][0]['content']['parts'][0]['text'];
            $results = json_decode($rawJson, true);

            // 5. Store in Database
            foreach ($results as $item) {
                Post::where('id', $item['id'])->update([
                    'ans' => $item['ans'],
                    'explanation' => $item['explanation']
                ]);
            }

            return "Successfully updated " . count($results) . " MCQs.";
        }

        Log::error("Gemini MCQ Error: " . $response->body());
        return "Failed to get response from Gemini.";
    }
}