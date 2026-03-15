<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Board;
use App\Models\Institution;
use App\Models\Subject;
use App\Services\Transformer\ModelService;
use App\Services\Image2WebpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class QuestionController extends Controller
{
    const PER_PAGE = 32;
    const MAX_IMAGES = 4;

    /**
     * Map AI numeric category output tokens back to DB strings.
     */
    protected array $categoryLabels = [1 => 'cq', 2 => 'mcq', 3 => 'writing'];

    public function __construct(protected ModelService $model)
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
        INDEX (Main Listing with AI Parameters)
    ===================================================== */
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));
        
        // Translate for internal logic processing
        $en_q = $this->translateBnToEn($q);
    
        if ($request->has('q') && $q === '') {
            return redirect()->route('questions.index');
        }
    
        $query = Post::query()->with(['institution', 'subject', 'board']);
    
        // 1. Sequential Parsing using the translated query
        if (!empty($en_q)) {
            $parsed = $this->parseSearchQuery($en_q);
            
            if (!empty($parsed)) {
                $query->where(function ($sub) use ($parsed) {
                    // Handle Institution
                    if (isset($parsed['institution'])) {
                        $sub->whereHas('institution', function($instQuery) use ($parsed) {
                            $instQuery->where('name', 'LIKE', $parsed['institution'] . '%');
                        });
                    }
    
                    // Handle Subject (Matches "Physics 1st", etc.)
                    if (isset($parsed['subject'])) {
                        $sub->whereHas('subject', function($subjQuery) use ($parsed) {
                            $subjQuery->where('name', 'like', $parsed['subject'] . '%');
                        });
                    }
    
                    // Handle Chapter
                    if (isset($parsed['chapter'])) {
                        $sub->where('posts.chapter', $parsed['chapter']);
                    }
                    
                    // Handle Year
                    if (isset($parsed['year'])) {
                        $sub->where('posts.year', 'LIKE', '%' . $parsed['year'] . '%');
                    }
                });
            }
        }
    
        $this->applyViewedStatus($query);
        
        // Use original $q for fuzzy scoring and view
        $this->applySearchAndScoring($query, $q);
    
        $posts = $query
            ->paginate(self::PER_PAGE)
            ->withQueryString()
            ->withPath(route('questions.index'));
    
        return view('questions.index', compact('posts', 'q'));
    }

    /* =====================================================
        SEARCH (Manual UI Filters - Unchanged)
    ===================================================== */
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        
        $clean = array_filter($request->query(), fn ($v) => $v !== null && $v !== '');
        if ($clean !== $request->query()) {
            return redirect()->route('search', $clean)->setStatusCode(301);
        }

        $query = Post::query()->with(['institution', 'subject', 'board']);
        $this->applyViewedStatus($query);

        $aiParams = !empty($q) ? $this->getParams($q, 0.80) : [];

        $filters = ['institution_id', 'subject_id', 'board_id', 'year', 'category', 'chapter'];
        
        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where("posts.$filter", $request->$filter);
            } 
            elseif (isset($aiParams[$filter])) {
                if ($filter === 'category') {
                    if (isset($this->categoryLabels[$aiParams[$filter]])) {
                        $query->where('posts.category', $this->categoryLabels[$aiParams[$filter]]);
                    }
                } else {
                    $query->where("posts.$filter", $aiParams[$filter]);
                }
            }
        }

        $posts = $query
            ->orderBy('was_viewed')
            ->orderByDesc('posts.year')
            ->orderByDesc('posts.created_at')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        if ($posts->isEmpty() && !empty($q)) {
            $fallbackQuery = Post::query()->with(['institution', 'subject', 'board']);
            $this->applyViewedStatus($fallbackQuery);
            $this->applySearchAndScoring($fallbackQuery, $q);
            $posts = $fallbackQuery->paginate(self::PER_PAGE)->withQueryString();
        }

        return view('pages.search', [
            'initialFilters' => $this->getAvailableFilters(),
            'posts' => $posts,
            'currentParams' => $request->all(),
            'aiParams' => $aiParams
        ]);
    }

    /* =====================================================
        AI / PARSING HELPERS
    ===================================================== */
    
    private function parseSearchQuery(string $q): array
    {
        // 1. Initial Cleaning & Bengali to English
        $q = enNum(strtolower(trim($q)));
        
        // Remove noise that shouldn't be in the Subject string or Chapter
        $q = preg_replace('/\b(paper|papr|cq|mcq|writing)\b/i', '', $q);
    
        $institution = null;
        $chapter = null;
        $subject = null;
        $year = null;
    
        // 2. Extract Year (4 digits) first so it doesn't get ordinal suffixes
        // Matches: 2025, 1998, 2026
        if (preg_match('/\b(19|20)\d{2}\b/', $q, $yearMatch)) {
            $year = $yearMatch[0];
            $q = str_replace($year, '', $q);
        } 
        // Matches 2-digit years > 20 (e.g., '25', '99') 
        // but ONLY if not preceded by "chapter" keywords
        elseif (preg_match('/\b(2[1-9]|[3-9]\d)\b/', $q, $yearMatch)) {
            // Check if the word "chapter" or "ch" appears right before this number
            // We do a quick lookbehind check
            if (!preg_match('/(chapter|ch|chap|adhay)\s+' . $yearMatch[0] . '/i', $q)) {
                $year = $yearMatch[0];
                $q = str_replace($year, '', $q);
            }
        }
    
        // 3. Identify Institution (AND REMOVE FROM STRING)
        $instMap = [
            'ssc' => 'SSC', 
            'hsc' => 'HSC', 
            'bcs' => 'BCS', 
            'departmental' => 'Departmental', 
            'dept' => 'Departmental'
        ];
        
        foreach ($instMap as $key => $val) {
            if (preg_match("/\b$key\b/i", $q)) {
                $institution = $val;
                // Crucial: Remove the institution word so it doesn't end up in the 'subject'
                $q = preg_replace("/\b$key\b/i", '', $q);
                break; 
            }
        }
    
        // 4. Handle Numbers (Subject Paper vs Chapter)
        $words = explode(' ', preg_replace('/\s+/', ' ', trim($q)));
        $numbers = [];
        foreach ($words as $index => $word) {
            $cleanNum = preg_replace('/[^0-9]/', '', $word);
            if (is_numeric($cleanNum) && strlen($cleanNum) < 4) {
                $numbers[] = ['val' => (int)$cleanNum, 'index' => $index];
            }
        }
    
        $chapterKeywordIdx = -1;
        $chapterKeywords = ['chapter', 'ch', 'chap', 'adhay', 'অধ্যায়', 'অধ্যায়'];
        foreach ($words as $i => $w) {
            if (in_array($w, $chapterKeywords)) {
                $chapterKeywordIdx = $i;
                break;
            }
        }
    
        $subjectNum = null;
        if (count($numbers) >= 2) {
            if ($chapterKeywordIdx !== -1) {
                foreach ($numbers as $n) {
                    if (abs($n['index'] - $chapterKeywordIdx) <= 1) {
                        $chapter = $n['val'];
                    } else {
                        $subjectNum = $n['val'];
                    }
                }
            } else {
                // Default: First number is Subject Paper, Second is Chapter
                $subjectNum = $numbers[0]['val'];
                $chapter = $numbers[1]['val'];
            }
        } elseif (count($numbers) === 1) {
            if ($chapterKeywordIdx !== -1) {
                $chapter = $numbers[0]['val'];
            } else {
                // If it's the only number, it's likely the Subject Paper (e.g. Physics 1st)
                $subjectNum = $numbers[0]['val'];
            }
        }
    
        // 5. Subject Construction
        // Filter out numbers, chapter keywords, and institution names (already removed)
        $ignore = array_merge($chapterKeywords, ['st', 'nd', 'rd', 'th']);
        $subjectWords = [];
        
        foreach ($words as $w) {
            $cleanW = preg_replace('/[0-9stndrdth]/i', '', $w);
            // Ensure we don't pick up empty strings or single characters
            if (!in_array($w, $ignore) && !is_numeric($w) && strlen($cleanW) > 1) {
                $subjectWords[] = (strtolower($w) === 'ict') ? 'ICT' : ucfirst($w);
            }
        }
    
        if (!empty($subjectWords)) {
            $subject = implode(' ', $subjectWords);
            if ($subjectNum) {
                $subject .= ' ' . ordinal_suffix($subjectNum);
            }
        }
    
        // 6. Return only existing keys
        $result = [];
        if ($institution) $result['institution'] = $institution;
        if ($subject)     $result['subject']     = $subject;
        if ($chapter)     $result['chapter']     = $chapter;
        if ($year)        $result['year']        = $year;
    
        return $result;
    }

    private function getParams(string $query, float $threshold = 0.75): array
    {
        $result = $this->model->predict($query);
        $predictions = $result['predictions'];
        $confidence  = $result['confidence'];
        
        $trustedParams = [];
        $map = [0 => 'institution_id', 1 => 'subject_id', 2 => 'year', 3 => 'board_id', 4 => 'chapter', 5 => 'category'];

        foreach ($map as $index => $key) {
            if (isset($confidence[$index]) && $confidence[$index] >= $threshold) {
                $trustedParams[$key] = $predictions[$key];
            }
        }
        return $trustedParams;
    }

    /* =====================================================
        CORE UTILITIES (Status, Scoring, & Filters)
    ===================================================== */

    private function applyViewedStatus($query)
    {
        $userId = auth()->id();
        if ($userId) {
            $query->select('posts.*')
                ->selectRaw('EXISTS (
                    SELECT 1 FROM viewed_posts 
                    WHERE viewed_posts.post_id = posts.id 
                    AND viewed_posts.user_id = ?
                ) AS was_viewed', [$userId]);
        } else {
            $query->select('posts.*')->selectRaw('0 AS was_viewed');
        }
    }

    private function applySearchAndScoring($query, $q)
    {
        if (!$q) {
            $query->orderBy('was_viewed')
                  ->orderByDesc('posts.year')
                  ->orderByDesc('posts.created_at');
            return;
        }
    
        $query->leftJoin('institutions', 'institutions.id', '=', 'posts.institution_id')
              ->leftJoin('subjects', 'subjects.id', '=', 'posts.subject_id')
              ->leftJoin('boards', 'boards.id', '=', 'posts.board_id');
    
        $words = array_filter(explode(' ', $q), fn($word) => strlen($word) >= 2);
        if (empty($words)) $words = [$q];
    
        $scoreSqlParts = [];
        $bindings = [];
    
        foreach ($words as $word) {
            $wildcard = "%{$word}%";
            $scoreSqlParts[] = "(CASE
                WHEN posts.topic_name LIKE ? THEN 90
                WHEN subjects.name LIKE ? THEN 85
                WHEN institutions.name LIKE ? THEN 80
                WHEN posts.year LIKE ? THEN 80
                WHEN posts.chapter LIKE ? THEN 75
                WHEN posts.article LIKE ? THEN 60
                WHEN boards.name LIKE ? THEN 60
                WHEN posts.category LIKE ? THEN 90
                ELSE 0 END)";
    
            array_push($bindings, $wildcard, $wildcard, $wildcard, $word, $word, $wildcard, $wildcard, $word);
        }
    
        $totalScoreSql = implode(' + ', $scoreSqlParts);
    
        $query->addSelect(DB::raw("($totalScoreSql) AS match_score"))
              ->addBinding($bindings, 'select')
              ->where(function ($sub) use ($words) {
                  foreach ($words as $word) {
                      $wildcard = "%{$word}%";
                      $sub->orWhere('posts.topic_name', 'LIKE', $wildcard)
                          ->orWhere('subjects.name', 'LIKE', $wildcard)
                          ->orWhere('institutions.name', 'LIKE', $wildcard)
                          ->orWhere('posts.year', 'LIKE', $word);
                  }
              })
              ->orderByDesc('match_score')
              ->orderBy('was_viewed')
              ->orderByDesc('year');
    }

    /* =====================================================
        CRUD METHODS
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
                if ($images) $question->update($images);
            }

            Cache::forget('question_filters');
            return redirect()->route('questions.show', ['question' => $question->id]);
        });
    }

    public function show(Post $question, $slug = null)
    {
        $realSlug = url_slug($question->article, question_meta_text($question));
        if ($slug !== $realSlug) {
            return redirect()->route('questions.show', ['question' => $question->id, 'slug' => $realSlug]);
        }
        return view('questions.show', ['post' => $question->load(['institution', 'subject', 'board', 'comments.user'])]);
    }

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
            if (file_exists($webp)) rename($webp, $final);
            if (file_exists($original)) unlink($original);
            $paths["image{$num}"] = str_replace(public_html_path() . '/', '', $final);
        }
        return $paths;
    }

    protected function getAvailableFilters()
    {
        return Cache::remember('question_filters', 86400, function() {
            $years = Post::distinct()->pluck('year')->filter()->map(fn($year) => explode('/', $year)[0])->unique()->sortDesc()->values();
            return [
                'institutions' => Institution::orderBy('name')->get(['id', 'name']),
                'boards' => Board::orderBy('name')->get(['id', 'name']),
                'years' => $years,
            ];
        });
    }

    public function getSubjectsByInstitution(Request $request) {
        if(!$request->institution_id) return response()->json([]);
        return response()->json(Subject::where('institution_id', $request->institution_id)->where('status', 1)->orderBy('name')->get(['id', 'name']));
    }

    public function subject($slug)
    {
        $searchTerm = str_replace('-', ' ', $slug);
        $subjectIds = Subject::where('name', 'LIKE', '%' . $searchTerm . '%')->pluck('id');
        if ($subjectIds->isEmpty()) abort(404);

        $posts = Post::query()
            ->whereIn('subject_id', $subjectIds)
            ->with(['institution', 'board', 'subject'])
            ->orderByDesc('year')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $q = ucwords($searchTerm);
        return view('questions.index', compact('posts', 'q'));
    }
    
    private function translateBnToEn(string $q): string
    {
        // 1. Bengali to English Numbers
        $q = enNum($q);
    
        // 2. Bengali Suffixes & Common Keywords
        $dictionary = [
            'ম' => 'st', // 1st -> ১ম
            'য়' => 'nd', // 2nd -> ২য়
            'ষ' => 'rd', // 3rd -> ৩য়
            'র্থ' => 'th', // 4th -> ৪র্থ
            'পত্র' => 'paper',
            'অধ্যায়' => 'chapter', // Uses regular 'Ya'
            'অধ্যায়' => 'chapter', // Uses 'Ya' with dot (Yya)
            'বিষয়' => 'subject',
        ];
    
        // 3. Core Subject Names Mapping
        $subjects = [
            'বাংলা' => 'Bangla',
            'ইংরেজি' => 'English',
            'ইংরেজী' => 'English',
            'আইসিটি' => 'ICT',
            'তথ্য ও যোগাযোগ প্রযুক্তি' => 'ICT',
            'পদার্থ' => 'Physics',
            'পদার্থবিজ্ঞান' => 'Physics',
            'বিজ্ঞান' => 'Science',
            'গণিত' => 'Math',
        ];
    
        // Apply translations for suffixes and keywords
        foreach ($dictionary as $bn => $en) {
            $q = str_replace($bn, $en, $q);
        }
    
        // Apply translations for subject names (using word boundaries or exact match)
        foreach ($subjects as $bn => $en) {
            $q = str_replace($bn, $en, $q);
        }
    
        return $q;
    }
}