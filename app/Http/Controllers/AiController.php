<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;
use App\Services\McqGeminiService;
use App\Models\Post;
use Illuminate\Support\Facades\File;

class AiController extends Controller
{
    protected $gemini, $mcq;
    // Mapping DB strings back to Model IDs
    protected array $categoryLabels = ['cq' => 1, 'mcq' => 2, 'writing' => 3];
    
    // Laravel automatically resolves this
    public function __construct(GeminiService $gemini, McqGeminiService $mcq)
    {
        $this->gemini = $gemini;
        $this->mcq = $mcq;
    }

    public function mcq()
    {
        set_time_limit(120);
        $response = $this->mcq->processBatch();
        return $response;
    }

    /**
     * Incremental Trainer: Processes one 'untrained' row per call.
     */
    public function trainOne()
    {
        set_time_limit(60);
        $response = $this->gemini->fillMissingTopics();
        $response = $this->gemini->fillMissingAnsAndExplanations();
        dd($response);

        // 1. Fetch the first row that hasn't been trained yet
        $post = Post::with(['institution', 'subject', 'board'])
            ->where('trained', 0)
            ->first();

        if (!$post) {
            return response()->json(['message' => 'All posts are already trained.'], 200);
        }

        // 2. Synthesize Training Text (Following your synthesis logic)
        $trainingText = $this->prepareTrainingText($post);
        
        // 3. Prepare Target Vector
        // Vector format: [Inst, Subj, Year, Board, Chap, Cat]
        $targets = [
            (int)($post->institution_id ?? 0),
            (int)($post->subject_id ?? 0),
            (int)($post->year ?? 0),
            (int)($post->board_id ?? 0),
            (int)($post->chapter ?? 0),
            $this->categoryLabels[strtolower($post->category)] ?? 0
        ];

        // 4. Training Loop (Max 20 epochs with early exit at < 1% loss)
        $finalLoss = 0;
        $epochsRun = 0;
        $learningRate = 0.05; // Slightly lower LR for stability in incremental training

        for ($i = 1; $i <= 20; $i++) {
            $epochsRun = $i;
            $finalLoss = $this->model->learn($trainingText, $targets, $learningRate);
            
            // Early exit if loss is less than 1% (0.01)
            if ($finalLoss < 0.0001) {
                break;
            }
        }

        // 5. Update the record as trained
        $post->update(['trained' => 1]);

        return response()->json([
            'status'     => 'Success',
            'post_id'    => $post->id,
            'input_text' => $trainingText,
            'targets'    => $targets,
            'epochs'     => $epochsRun,
            'final_loss' => round($finalLoss, 6)
        ]);
    }

    /**
     * Text Synthesis Logic: Creates a training phrase from DB row
     */
    private function prepareTrainingText($post): string
    {
        // Using helpers from your example
        $instName = institution($post->institution?->name); 
        $subjName = subject($post->subject?->name);
        $boardName = $post->board?->name ?? '';
        $catStr = strtolower($post->category ?? '');
        
        $words = [$instName, $subjName, $catStr];

        if ($post->institution_id == 4 && $post->year > 0) {
            // BCS: "45th BCS", "45", etc.
            $words[] = (rand(1, 2) === 1) ? $post->year : ordinal_suffix($post->year) . " BCS";
        } else {
            if ($post->year > 0) $words[] = $post->year;
            if ($boardName) $words[] = $boardName . " Board";
        }

        if ($post->chapter) {
            $words[] = "Chapter " . $post->chapter;
        }

        // Simulate a partial query (Dropout)
        $selected = array_filter($words, fn($w) => !empty($w) && rand(1, 100) <= 90);
        $text = implode(' ', $selected);

        return empty($text) ? "{$instName} {$subjName}" : preg_replace('/\s+/', ' ', trim($text));
    }

    /**
     * SEARCH: Simple real-time inference.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        if (empty($query)) {
            return response()->json(['error' => 'No query provided'], 400);
        }

        // The model is now a "black box": Raw String in -> IDs out.
        $result = $this->model->predict($query);

        return response()->json([
            'query' => $query,
            'predictions' => $result['predictions'],
            'confidence_scores' => $result['confidence']
        ]);
    }

    /**
     * RESET: Wipe weights.
     */
    public function reset()
    {
        $this->model->initializeWeights();
        return response()->json(['status' => 'Model reset. Weights re-initialized.']);
    }
    
    /**
     * Bulk Trainer: Processes every record in the DB for a single epoch.
     * Useful for initial model grounding.
     */
    /**
     * Bulk Trainer: Processes records within a specific ID range.
     * * @param int $from Starting ID
     * @param int $to   Ending ID
     */
    public function trainAll()
    {
        set_time_limit(0); 

        // Get range from request or default to 0 and max integer
        $from = 1;
        $to = 100;
        
        $totalProcessed = 0;
        $learningRate = 0.05;
        $startTime = microtime(true);

        // Filter by the ID range before chunking
        Post::with(['institution', 'subject', 'board'])
            ->where('id', '>=', $from)
            ->where('id', '<=', $to)
            ->orderBy('id', 'asc')
            ->chunk(100, function ($posts) use (&$totalProcessed, $learningRate) {
                foreach ($posts as $post) {
                    $trainingText = $this->prepareTrainingText($post);

                    $targets = [
                        (int)($post->institution_id ?? 0),
                        (int)($post->subject_id ?? 0),
                        (int)($post->year ?? 0),
                        (int)($post->board_id ?? 0),
                        (int)($post->chapter ?? 0),
                        $this->categoryLabels[strtolower($post->category)] ?? 0
                    ];

                    $this->model->learn($trainingText, $targets, $learningRate);
                    $totalProcessed++;
                }
            });

        $executionTime = round(microtime(true) - $startTime, 2);

        return response()->json([
            'status' => "Bulk training complete for IDs {$from} to {$to}",
            'total_rows_processed' => $totalProcessed,
            'execution_time_seconds' => $executionTime,
            'average_speed' => $totalProcessed > 0 ? round($totalProcessed / $executionTime, 2) . ' rows/sec' : 0
        ]);
    }
    
    /**
     * OCR Processor: Processes images in public/uploads/ocr_queue
     */
    public function processOcrQueue()
    {
        set_time_limit(120);
        $directory = public_html_path('images/ocr_queue_hsc');
        $institution_id = 2; // HSC
    
        $files = File::files($directory);
        $firstFile = !empty($files) ? $files[0] : null;
    
        if (!$firstFile) {
            return response()->json(['message' => 'No files to process.']);
        }
    
        $filePath = $firstFile->getRealPath();
        
        // --- Parse Metadata from Filename ---
        // Example: physics-1st.dhaka.1.jpg -> ['physics-1st', 'dhaka', '1']
        $filename = pathinfo($firstFile->getFilename(), PATHINFO_FILENAME);
        $parts = explode('.', $filename);
        
        $subjectSlug = $parts[0] ?? null;
        $boardName   = (count($parts) > 2) ? $parts[1] : null;
    
        // Call the service with the parsed metadata
        $questions = $this->gemini->askGeminiWithImage([
            'filePath'       => $filePath,
            'institution_id' => $institution_id,
            'subject_slug'   => $subjectSlug,
            'board_name'     => $boardName
        ]);
    
        if ($questions && is_array($questions) && isset($questions[0]['article'])) {
            foreach ($questions as $q) {
                // SAFETY CHECK: Ensure $q is an array. 
                // If it's a string, we skip it or log it as an error.
                if (!is_array($q)) {
                    Log::warning("Skipping malformed OCR item: " . json_encode($q));
                    continue;
                }
        
                Post::create([
                    'article'          => $q['article'] ?? '',
                    'a'                => $q['a'] ?? null,
                    'b'                => $q['b'] ?? null,
                    'c'                => $q['c'] ?? null,
                    'd'                => $q['d'] ?? null,
                    'category'         => $q['category'] ?? 'MCQ',
                    'answer'           => $q['ans'] ?? null,
                    'q_no'             => $q['q_no'] ?? null,
                    'has_complex_html' => $q['has_complex_html'] ?? 0,
                    'topic_name'       => $q['topic_name'] ?? 'General',
                    'chapter'          => $q['chapter'] ?? null,
                    'institution_id'   => $institution_id,
                    'subject_id'       => $q['subject_id'] ?? null,
                    'board_id'         => $q['board_id'] ?? null,
                    'year'             => $q['year'] ?? 2025,
                    'user_id'          => 1,
                    'is_verified'      => 1,
                ]);
            }
        
            File::delete($filePath);
    
            return response()->json([
                'status' => 'success',
                'file_processed' => $firstFile->getFilename()
            ]);
        }
    
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to process the file.'
        ]);
    }
}