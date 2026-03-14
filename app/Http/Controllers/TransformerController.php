<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Transformer\ModelService;
use App\Models\Post;

class TransformerController extends Controller
{
    // Mapping DB strings back to Model IDs
    protected array $categoryLabels = ['cq' => 1, 'mcq' => 2, 'writing' => 3];

    public function __construct(
        protected ModelService $model
    ) {}

    /**
     * Incremental Trainer: Processes one 'untrained' row per call.
     */
    public function trainOne()
    {
        set_time_limit(60);

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
            if ($finalLoss < 0.01) {
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
}