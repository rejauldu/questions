<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Board;
use App\Models\Institution;
use App\Models\Subject;
use App\Services\AiSearchService;
use App\Services\Image2WebpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class QuestionController extends Controller
{
    const PER_PAGE = 32;
    const MAX_IMAGES = 4;

    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'update']);
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->role !== 'admin') {
                abort(403, 'Admin access only.');
            }
            return $next($request);
        })->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /* =====================================================
        INDEX (AI-Enhanced)
    ===================================================== */
    public function index(Request $request, AiSearchService $aiService)
    {
        $q = trim($request->input('q'));

        if ($request->has('q') && $q === '') {
            return redirect()->route('questions.index');
        }

        $query = Post::query()->with(['institution', 'subject', 'board']);

        if ($q) {
            $aiParams = $aiService->extractParameters($q);
            if (!empty($aiParams)) {
                $query->where(function ($sub) use ($aiParams, $aiService) {
                    foreach ($aiService->getMapKeys() as $key) {
                        if (isset($aiParams[$key])) {
                            // এখানে 'posts.' প্রিফিক্স যোগ করা হয়েছে
                            $sub->where('posts.' . $key, $aiParams[$key]);
                        }
                    }
                });
            }
        }

        $this->applyViewedStatus($query);
        $this->applySearchAndScoring($query, $q);

        $posts = $query
            ->paginate(self::PER_PAGE)
            ->withQueryString()
            ->withPath(route('questions.index'));

        return view('questions.index', compact('posts', 'q'));
    }

    /* =====================================================
        SEARCH (FILTER PAGE)
    ===================================================== */
    public function search(Request $request)
    {
        $clean = array_filter($request->query(), fn ($v) => $v !== null && $v !== '');
        if ($clean !== $request->query()) {
            return redirect()->route('search', $clean)->setStatusCode(301);
        }

        $query = Post::query()->with(['institution', 'subject', 'board']);
        $this->applyViewedStatus($query);

        foreach (['institution_id', 'subject_id', 'board_id', 'year', 'category'] as $filter) {
            if ($request->filled($filter)) {
                $query->where("posts.$filter", $request->$filter);
            }
        }

        $posts = $query
            ->orderBy('was_viewed')
            ->orderByDesc('posts.year')
            ->orderByDesc('posts.created_at')
            ->paginate(self::PER_PAGE)
            ->withQueryString()
            ->withPath(route('search'));

        return view('pages.search', [
            'initialFilters' => $this->getAvailableFilters(),
            'posts' => $posts,
            'currentParams' => $request->all(),
        ]);
    }

    /* =====================================================
        STORE
    ===================================================== */
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->except(['_token', 'images']);

            $data['article'] = clean_html_between_tags($data['article'] ?? '');
            $data['explanation'] = clean_html_between_tags($data['explanation'] ?? '');

            $question = Post::create($data);

            if ($request->hasFile('images')) {
                $images = $this->processAndStoreImages($request->file('images'), $question);
                if ($images) {
                    $question->update($images);
                }
            }

            Cache::forget('question_filters');
            return redirect()->route('questions.show', ['question' => $question->id]);
        });
    }

    /* =====================================================
        SHOW
    ===================================================== */
    public function show(Post $question, $slug = null)
    {
        $realSlug = url_slug($question->article, question_meta_text($question));

        if ($slug !== $realSlug) {
            return redirect()->route('questions.show', [
                'question' => $question->id,
                'slug' => $realSlug
            ]);
        }

        return view('questions.show', [
            'post' => $question->load(['institution', 'subject', 'board', 'comments.user'])
        ]);
    }

    /* =====================================================
        UPDATE
    ===================================================== */
    public function update(Request $request, Post $question)
    {
        $request->validate(['article' => 'required']);

        $data = $request->except(['_token', '_method', 'images']);
        $data['article'] = clean_html_between_tags($data['article']);
        if (isset($data['explanation'])) {
            $data['explanation'] = clean_html_between_tags($data['explanation']);
        }

        $question->update($data);
        Cache::forget('question_filters');

        return redirect()->route('questions.show', ['question' => $question->id]);
    }

    /* =====================================================
        HELPERS
    ===================================================== */

    /**
     * Injects viewed status using a subquery to avoid JOIN/GROUP BY errors.
     */
    private function applyViewedStatus($query)
    {
        if (auth()->check()) {
            $userId = auth()->id();

            // Using a subquery for 'was_viewed' satisfies ONLY_FULL_GROUP_BY 
            // because it doesn't create extra rows in the primary result set.
            $query->select('posts.*')
                ->selectRaw('EXISTS (
                    SELECT 1 FROM viewed_posts 
                    WHERE viewed_posts.post_id = posts.id 
                    AND viewed_posts.user_id = ?
                ) AS was_viewed', [$userId]);
        } else {
            $query->select('posts.*')
                  ->selectRaw('0 AS was_viewed');
        }
    }

    /**
     * Handles search relevancy. 
     * Note: We removed the JOINs here to keep pagination clean and error-free.
     */
    private function applySearchAndScoring($query, $q)
    {
        if (!$q) {
            $query->orderBy('was_viewed')
                  ->orderByDesc('posts.year')
                  ->orderByDesc('posts.created_at');
            return;
        }

        // We join only when searching to calculate scores
        $query->leftJoin('institutions', 'institutions.id', '=', 'posts.institution_id')
              ->leftJoin('subjects', 'subjects.id', '=', 'posts.subject_id')
              ->leftJoin('boards', 'boards.id', '=', 'posts.board_id');

        $full = "%{$q}%";
        // Reordered to match the CASE statement logic
        $scoreBindings = [$full, $full, $full, $full, $q, $q, $full]; 

        $scoreSql = "(CASE
            WHEN posts.article LIKE ? THEN 100
            WHEN posts.topic_name LIKE ? THEN 90
            WHEN subjects.name LIKE ? THEN 85
            WHEN institutions.name LIKE ? THEN 80
            WHEN posts.chapter = ? THEN 75
            WHEN posts.year = ? THEN 70
            WHEN boards.name LIKE ? THEN 60
            ELSE 0 END)";

        $query->addSelect(DB::raw("$scoreSql AS match_score"))
              ->addBinding($scoreBindings, 'select')
              ->where(function ($sub) use ($full) {
                  $sub->where('posts.article', 'LIKE', $full)
                    ->orWhere('institutions.name', 'LIKE', $full)
                    ->orWhere('subjects.name', 'LIKE', $full)
                    ->orWhere('boards.name', 'LIKE', $full)
                    ->orWhere('posts.chapter', 'LIKE', $full)
                    ->orWhere('posts.topic_name', 'LIKE', $full)
                    ->orWhere('posts.year', 'LIKE', $full);
              })
              ->orderByDesc('match_score')
              ->orderBy('was_viewed');
    }

    private function processAndStoreImages($files, Post $question)
    {
        $paths = [];
        $dir = public_html_path('images/questions');
        $converter = new Image2WebpService();

        foreach (array_slice($files, 0, self::MAX_IMAGES) as $i => $file) {
            $num = $i + 1;
            $base = question_image_basename($question->toArray());

            $tmp = $base . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $tmp);

            $original = "$dir/$tmp";
            $webp = $converter->convert($original, 800, 80, $num);

            $final = "$dir/{$base}-{$num}-{$question->id}.webp";
            
            if (file_exists($webp)) {
                rename($webp, $final);
            }

            if (file_exists($original)) unlink($original);

            $paths["image{$num}"] = str_replace(public_html_path() . '/', '', $final);
        }

        return $paths;
    }

    protected function getAvailableFilters()
    {
        $years = Post::distinct()->pluck('year')->filter()->map(fn($year) => explode('/', $year)[0])->unique()->sortDesc()->values();
        
        return Cache::remember('question_filters', 86400, fn () => [
            'institutions' => Institution::orderBy('name')->get(['id', 'name']),
            'boards' => Board::orderBy('name')->get(['id', 'name']),
            'years' => $years,
        ]);
    }
    
    public function getSubjectsByInstitution(Request $request) {
        if(!$request->institution_id) return response()->json([]);
        
        $subjects = Subject::where('institution_id', $request->institution_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return response()->json($subjects);
    }
    
    public function createBlade()
    {
        return view('questions.create-blade', [
            'institutions' => Institution::orderBy('name')->get(['id', 'name']),
            'boards' => Board::orderBy('name')->get(['id', 'name']),
            'years' => range(date('Y'), date('Y')-5),
            'classes' => [
                ['value' => 1, 'text' => '1st Year'],
                ['value' => 2, 'text' => '2nd Year'],
                ['value' => 3, 'text' => '3rd Year'],
                ['value' => 4, 'text' => '4th Year'],
            ]
        ]);
    }
    
    /**
     * Display posts filtered by a specific subject (SEO Friendly).
     */
    public function subject($slug)
    {
        // 1. Convert slug 'higher-math' to 'Higher Math' or 'physics' to 'Physics'
        $searchTerm = str_replace('-', ' ', $slug);
    
        // 2. Fetch all Subject IDs that match the search term (e.g., Physics-1, Physics-2)
        $subjectIds = Subject::where('name', 'LIKE', '%' . $searchTerm . '%')
            ->pluck('id');
    
        if ($subjectIds->isEmpty()) {
            abort(404);
        }
    
        // 3. Build the query for posts matching any of those subject IDs
        $posts = Post::query()
            ->whereIn('subject_id', $subjectIds)
            ->with(['institution', 'board', 'subject'])
            ->orderByDesc('year')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();
    
        // 4. Set $q for the search box display
        $q = ucwords($searchTerm);
    
        return view('questions.index', compact('posts', 'q'));
    }
}