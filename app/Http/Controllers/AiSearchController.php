<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiSearchService;
use App\Models\Post;

class AiSearchController extends Controller
{
    /**
     * Map AI numeric output tokens back to DB strings.
     */
    protected array $categoryLabels = [1 => 'cq', 2 => 'mcq', 3 => 'writing'];

    public function __construct(protected AiSearchService $aiSearch) {}

    /* =====================================================
        SEARCH
    ===================================================== */
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (empty($q)) return response()->json(['results' => [], 'count' => 0]);

        // 1. AI parameter extraction
        $params = $this->aiSearch->extractParameters($q);

        dd($params);
        
        $query = Post::query()->with(['institution', 'subject', 'board']);

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if ($value <= 0) continue;

                if ($key === 'year') {
                    $instId = $params['institution_id'] ?? 0;
                    $actualYear = ($instId === 4) ? $value : ($value < 100 ? $value + 2000 : $value);
                    $query->where('year', $actualYear);
                } elseif ($key === 'category') {
                    if (isset($this->categoryLabels[$value])) {
                        $query->where('category', $this->categoryLabels[$value]);
                    }
                } else {
                    $query->where($key, $value);
                }
            }
        }

        $posts = $query->latest()->limit(15)->get();

        // 2. Fallback to LIKE search
        if ($posts->isEmpty()) {
            $posts = Post::where('article', 'LIKE', "%{$q}%")->latest()->limit(10)->get();
        }

        // 3. Response with parameters included in each row
        return response()->json([
            'input'      => $q,
            'prediction' => $params, // Global prediction for the query
            'count'      => $posts->count(),
            'results'    => $posts->map(fn($p) => [
                'id'         => $p->id,
                'article'    => $p->article,
                'meta'       => question_meta_text($p),
                'ai_params'  => $params, // Returning extracted parameters in each row
            ])
        ]);
    }

    /* =====================================================
        TRAIN
    ===================================================== */
    public function train(Request $request)
    {
        set_time_limit(0); 

        // 1. Fetch random posts for diverse training
        $posts = Post::with(['institution', 'subject', 'board'])
            ->inRandomOrder() 
            ->limit(1000)
            ->get();
        
        $logs = [];
        $categoryFlip = array_flip($this->categoryLabels);

        foreach ($posts as $index => $post) {
            $instId   = (int) ($post->institution_id ?? 0);
            $year     = (int) ($post->year ?? 0);
            $instName = institution($post->institution?->name); // Using your helper
            $subjName = subject($post->subject?->name);         // Using your helper
            $catStr   = strtolower($post->category ?? '');
            
            // 2. Text Synthesis with Randomness (Data Augmentation)
            $words = [$instName, $subjName, $catStr];

            if ($instId === 4 && $year > 0) {
                // BCS Logic: 45, 45th, 45th BCS
                $suffix = ordinal_suffix($year); // assumed helper
                $words[] = $this->either(
                    $this->either($year, bnNum($year)), 
                    $this->either($suffix, $suffix . " BCS")
                );
            } else {
                if ($year > 0) {
                    $words[] = $this->either($year, bnNum($year));
                }
                $boardEn = $post->board?->name ?? '';
                if (!empty($boardEn)) {
                    $words[] = $this->either($boardEn . " Board", bnBoard($boardEn) . " বোর্ড");
                }
            }

            if ($post->chapter) {
                $words[] = $this->either(
                    "Chapter " . $this->either($post->chapter, ordinal_suffix($post->chapter)), 
                    bnNum($post->chapter) . " অধ্যায়"
                );
            }

            // 3. Random Dropout (85% probability) to simulate partial queries
            $selected = array_filter($words, fn($w) => !empty($w) && rand(1, 100) <= 85);
            $trainingText = implode(' ', $selected);
            if (empty($trainingText)) $trainingText = "{$instName} {$subjName}";
            $trainingText = preg_replace('/\s+/', ' ', trim($trainingText));

            // 4. Prepare Target Data
            $targetData = [
                'institution_id' => $instId,
                'subject_id'     => (int) ($post->subject_id ?? 0),
                'year'           => ($instId === 4) ? $year : ($year > 2000 ? $year - 2000 : $year),
                'board_id'       => (int) ($post->board_id ?? 0),
                'chapter'        => (int) ($post->chapter ?? 0),
                'category'       => $categoryFlip[$catStr] ?? 0,
            ];

            // 5. Training
            $this->aiSearch->train($trainingText, $targetData, lr: 0.1, epochs: 100);

            if ($index % 100 === 0) {
                $logs[] = [
                    'row'    => $index,
                    'text'   => mb_substr($trainingText, 0, 50),
                    'target' => $targetData
                ];
            }
        }

        return response()->json([
            'message' => 'Training successful with randomness and suffixes!',
            'count'   => $posts->count(),
            'logs'    => $logs
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Helper for random variation in training text
     */
    private function either($a, $b)
    {
        return (rand(1, 100) <= 70) ? $a : $b;
    }
}