<?php

namespace App\Http\Controllers;

use App\Models\{Post, Board, Institution, Subject};
use App\Services\Image2WebpService;
use App\Traits\ParsesSearchQueries;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Cache, Redis, Auth, Redirect};
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    use ParsesSearchQueries;

    const PER_PAGE = 32;
    const MAX_IMAGES = 4;

    /**
     * Constructor for Middleware handling.
     */
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

    /**
     * Display a listing of questions.
     */
    public function index(Request $request)
    {
        $rawQ = trim($request->input('q', ''));
        
        if ($request->has('q') && $rawQ === '') {
            return redirect()->route('questions.index');
        }

        $query = Post::query()->with(['institution', 'subject', 'board']);

        if (!empty($rawQ)) {
            $parsed = $this->parseSearchQuery($rawQ);

            // 1. Priority ID Search
            if (isset($parsed['id'])) {
                $query->where('posts.id', $parsed['id']);
            } 
            else {
                // 2. Metadata Filtering Mode
                $hasParameters = (
                    isset($parsed['institution']) || 
                    isset($parsed['board']) || 
                    isset($parsed['subject']) || 
                    isset($parsed['year']) || 
                    isset($parsed['category']) || 
                    isset($parsed['chapter'])
                );

                if ($hasParameters) {
                    if (isset($parsed['institution'])) {
                        $query->whereHas('institution', function($i) use ($parsed) {
                            $i->where('name', $parsed['institution']);
                        });
                    }
                    
                    if (isset($parsed['board'])) {
                        $query->whereHas('board', function($b) use ($parsed) {
                            $b->where('name', $parsed['board']);
                        });
                    }
    
                    if (isset($parsed['subject'])) {
                        $query->whereHas('subject', function($s) use ($parsed) {
                            $s->where('name', $parsed['subject']);
                        });
                    }
                    
                    if (isset($parsed['year'])) {
                        $query->where('posts.year', 'LIKE', "%{$parsed['year']}%");
                    }
                    
                    if (isset($parsed['category'])) {
                        $query->where('posts.category', $parsed['category']);
                    }
                    
                    if (isset($parsed['chapter'])) {
                        $query->where('posts.chapter', $parsed['chapter']);
                    }

                    $query->orderByDesc('posts.year')->orderByDesc('posts.created_at');
                } else {
                    // 3. Weighted Search Mode (Fallback)
                    $searchString = !empty($parsed['clean_query']) ? $parsed['clean_query'] : $rawQ;
                    $this->applySearchAndScoring($query, $searchString);
                }
            }
        } else {
            $query->orderByDesc('posts.year')->orderByDesc('posts.created_at');
        }

        $posts = $query->whereIn('category', ['CQ', 'MCQ', 'Writing'])->paginate(self::PER_PAGE)->withQueryString();
        
        return view('questions.index', [
            'posts' => $posts, 
            'q' => $rawQ 
        ]);
    }

    /**
     * Advanced Parameter-based Search.
     */
    public function search(Request $request)
    {
        $clean = array_filter($request->query(), fn ($v) => $v !== null && $v !== '');
        if ($clean !== $request->query()) {
            return redirect()->route('search', $clean)->setStatusCode(301);
        }
    
        $query = Post::query()->with(['institution', 'subject', 'board']);
        $filters = ['institution_id', 'subject_id', 'board_id', 'year', 'category', 'chapter'];
        $hasActiveFilters = false;
    
        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $hasActiveFilters = true;
                if ($filter === 'year') {
                    $query->where("posts.$filter", 'LIKE', "%{$request->$filter}%");
                } else {
                    $query->where("posts.$filter", $request->$filter);
                }
            }
        }
    
        $posts = $query->orderByDesc('posts.year')
                       ->orderByDesc('posts.created_at')
                       ->paginate(self::PER_PAGE)
                       ->withQueryString();
    
        return view('pages.search', [
            'initialFilters' => $this->getAvailableFilters(), 
            'posts'          => $posts, 
            'currentParams'  => $request->all(),
            'hasFilters'     => $hasActiveFilters
        ]);
    }

    /* --- Core Search Utilities --- */

    /**
     * Optimized Search with Weighted Scoring and Threshold discarding.
     */
    private function applySearchAndScoring($query, $q)
    {
        if (!$q) return $query;
    
        $cacheKey = 'search_ids_weighted_v2_' . md5(strtolower($q));
        
        $postIds = Cache::remember($cacheKey, 0, function() use ($q) {
            // Prepare the raw query string for comparison (no spaces, stripped tags)
            $normalizedForComparison = preg_replace('/[^a-z0-9\x{0980}-\x{09FF}]/u', '', strtolower(strip_tags($q)));
            
            $words = array_filter(explode(' ', $q), fn($word) => mb_strlen($word) >= 2) ?: [$q];
            
            $subQuery = Post::query()
                ->leftJoin('subjects', 'subjects.id', '=', 'posts.subject_id');
    
            $scoreSql = [];
            $bindings = [];
    
            // A. Exact Match on short_article (Replaces hash_a comparison)
            $scoreSql[] = "(CASE WHEN posts.short_article = ? THEN 500 ELSE 0 END)";
            $bindings[] = $normalizedForComparison;
    
            // B. Prefix Match
            $scoreSql[] = "(CASE WHEN posts.short_article LIKE ? THEN 350 ELSE 0 END)";
            $bindings[] = mb_substr($normalizedForComparison, 0, 50) . '%';
    
            // C. Keyword Scoring
            foreach ($words as $word) {
                $wild = "%$word%";
                $scoreSql[] = "(CASE 
                    WHEN posts.topic_name LIKE ? THEN 100
                    WHEN posts.short_article LIKE ? THEN 80
                    WHEN posts.hash_a LIKE ? THEN 80
                    WHEN subjects.name LIKE ? THEN 60
                    ELSE 0 END)";
                array_push($bindings, $wild, $wild, $wild, $wild);
            }
    
            return $subQuery->select('posts.id')
                ->selectRaw("(" . implode(' + ', $scoreSql) . ") AS match_score", $bindings)
                ->having('match_score', '>=', 50)
                ->orderByDesc('match_score')
                ->limit(200)
                ->pluck('id')
                ->toArray();
        });
    
        if (!empty($postIds)) {
            $query->whereIn('posts.id', $postIds)
                  ->orderByRaw('FIELD(posts.id, ' . implode(',', $postIds) . ')');
        } else {
            $query->where('posts.id', 0); 
        }
    }

    /**
     * Efficiently attach viewed status to a collection.
     */
    private function attachViewedStatusToCollection($posts)
    {
        $uuid = request()->cookie('examdao_uuid') ?? (auth()->check() ? auth()->id() : 'guest');
        $viewedIds = Redis::smembers("viewed_set:{$uuid}");
        foreach ($posts as $post) {
            $post->was_viewed = in_array($post->id, $viewedIds);
        }
    }

    public function show(SeoService $seoService, Post $question, $slug = null)
    {
        $realSlug = url_slug($question->article, question_meta_text($question));
        if ($slug !== $realSlug) {
            return redirect()->route('questions.show', ['question' => $question->id, 'slug' => $realSlug]);
        }
        $post = Cache::remember("post_show_{$question->id}", 600, function() use ($question) {
            return $question->load(['institution', 'subject', 'board', 'comments.user']);
        });
        $seo = $seoService->generate($post);
        return view('questions.show', ['post' => $post, 'seo' => $seo]);
    }
    
    public function next()
    {
        $categories = ['CQ', 'Writing'];

        $post = Post::whereIn('category', $categories)
            ->where(function ($query) {
                $query->whereNull('explanation')
                      ->orWhere('explanation', '');
            })
            ->first();
        if($post)
         return redirect()->route('questions.show', $post->id);
        else
        return redirect()->back();
    }

    public function create()
    {
        return view('questions.create', [
            'institutions' => Institution::orderBy('name')->get(['id', 'name']),
            'boards' => Board::orderBy('name')->get(['id', 'name']),
            'years' => range(date('Y'), date('Y') - 10),
        ]);
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->except(['_token', 'images']);
            $data['article'] = isset($data['article']) ? clean_html_between_tags($data['article']) : '';
            $data['explanation'] = isset($data['explanation']) ? clean_html_between_tags($data['explanation']) : '';
            $question = Post::create($data);
            if ($request->hasFile('images')) {
                $paths = $this->processAndStoreImages($request->file('images'), $question);
                if ($paths) $question->update($paths);
            }
            Cache::forget('question_filters_v2');
            return redirect()->route('questions.show', ['question' => $question->id]);
        });
    }
    /**
     * Stores clipboard text into the 'article' field if it is currently empty.
     */
    public function explanationStore(Request $request)
    {
        // 1. Validate the incoming clipboard data and a post ID
        // Note: Ensure your JS sends 'post_id' along with the 'content'
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ]);

        // 2. Locate the post
        $post = Post::findOrFail($request->post_id);

        // 3. Check if the article field is null or an empty string
        if (empty($post->explanation)) {
            $post->update([
                'explanation' => $request->content
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Article updated successfully.'
            ], 200);
        }

        // 4. Return a conflict error if the field is already filled
        return response()->json([
            'success' => false, 
            'message' => 'The article field already contains data.'
        ], 409); 
    }

    public function edit(string $id)
    {
        $question = Post::findOrFail($id);
        return view('questions.edit', [
            'question'     => $question,
            'institutions' => Institution::orderBy('name')->get(['id', 'name']),
            'boards'       => Board::orderBy('name')->get(['id', 'name']),
            'years'        => range(date('Y'), date('Y') - 10),
            'classes'      => [
                ['value' => 1, 'text' => '1st Year'],
                ['value' => 2, 'text' => '2nd Year'],
                ['value' => 3, 'text' => '3rd Year'],
                ['value' => 4, 'text' => '4th Year'],
            ]
        ]);
    }

    public function update(Request $request, Post $question)
    {
        $request->validate(['article' => 'required']);
        $data = $request->except(['_token', '_method', 'images']);
        $data['article'] = clean_html_between_tags($data['article']);
        if (isset($data['explanation'])) $data['explanation'] = clean_html_between_tags($data['explanation']);
        $question->update($data);
        Cache::forget("post_show_{$question->id}");
        Cache::forget('question_filters_v2');
        return redirect()->route('questions.show', ['question' => $question->id]);
    }

    public function destroy(Post $question)
    {
        Cache::forget("post_show_{$question->id}");
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Deleted successfully');
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
            $webp = $converter->convert("$dir/$tmp", 800, 80, $num);
            $final = "$dir/{$base}-{$num}-{$question->id}.webp";
            if (file_exists($webp)) rename($webp, $final);
            if (file_exists("$dir/$tmp")) unlink("$dir/$tmp");
            $paths["image{$num}"] = str_replace(public_html_path() . '/', '', $final);
        }
        return $paths;
    }

    protected function getAvailableFilters()
    {
        return Cache::remember('question_filters_v2', 86400, fn() => [
            'institutions' => Institution::orderBy('name')->get(['id', 'name'])->toArray(),
            'boards' => Board::orderBy('name')->get(['id', 'name'])->toArray(),
            'years' => Post::distinct()->whereNotNull('year')->pluck('year')->map(fn($y) => explode('/', $y)[0])->unique()->sortDesc()->values()->toArray(),
        ]);
    }

    public function getSubjectsByInstitution(Request $request) 
    {
        if(!$request->institution_id) return response()->json([]);
        return response()->json(Subject::where('institution_id', $request->institution_id)->where('status', 1)->orderBy('name')->get(['id', 'name']));
    }

    public function subject($slug)
    {
        $term = str_replace('-', ' ', $slug);
        $ids = Subject::where('name', 'LIKE', "%$term%")->pluck('id');
        if ($ids->isEmpty()) abort(404);
        $posts = Post::whereIn('subject_id', $ids)->with(['institution', 'board', 'subject'])->orderByDesc('year')->paginate(self::PER_PAGE)->withQueryString();
        $q = ucwords($term);
        return view('questions.index', compact('posts', 'q'));
    }

    private function getParams(string $query, float $threshold = 0.75): array
    {
        $res = $this->model->predict($query);
        $map = ['institution_id', 'subject_id', 'year', 'board_id', 'chapter', 'category'];
        $trusted = [];
        foreach ($map as $i => $key) {
            if (($res['confidence'][$i] ?? 0) >= $threshold) {
                $trusted[$key] = $res['predictions'][$key];
            }
        }
        return $trusted;
    }
}