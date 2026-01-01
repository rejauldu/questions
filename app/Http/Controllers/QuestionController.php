<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Board;
use App\Models\Post;
use App\Models\Institution;
use App\Models\Subject;
use App\Services\Image2WebpService;
use Illuminate\Support\Facades\Validator;
use DB;

class QuestionController extends Controller
{
    public function __construct()
    {
        // 1. First, ensure the user is logged in for these actions
        $this->middleware('auth')->only(['create', 'store', 'update']);

        // 2. Second, ensure the logged-in user is an admin for creating/storing
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized action. Admin access only.');
            }
            return $next($request);
        })->only(['create', 'store']);
    }
    
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request) {
        $q = trim($request->input('q'));

        // Only redirect if query string 'q' exists but is empty
        if ($request->has('q') && $q === '') {
            return redirect()->route('questions.index');
        }

        $perPage = 10;

        $query = Post::query()
            ->with(['institution', 'subject', 'board'])
            ->leftJoin('institutions', 'institutions.id', '=', 'posts.institution_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'posts.subject_id')
            ->leftJoin('boards', 'boards.id', '=', 'posts.board_id');

        $user = auth()->user();

        // 1. Join Viewed Posts & Select Base Columns
        if ($user) {
            $query->leftJoin('viewed_posts', function($join) use ($user) {
                $join->on('posts.id', '=', 'viewed_posts.post_id')
                    ->where('viewed_posts.user_id', '=', $user->id);
            })
            ->selectRaw('posts.*, CASE WHEN viewed_posts.id IS NULL THEN 0 ELSE 1 END as was_viewed');
        } else {
            $query->selectRaw('posts.*, 0 as was_viewed');
        }

        if ($q) {
            $words = preg_split('/\s+/', $q);
            $fullSearch = "%{$q}%";
            
            // 2. Build Relevance Scoring with SelectRaw
            // We use an array for bindings to keep it clean
            $scoreBindings = [$fullSearch, $fullSearch, $fullSearch, $fullSearch, $fullSearch, $fullSearch];
            
            $scoreSql = "(CASE
                WHEN posts.article LIKE ? THEN 75
                WHEN institutions.name LIKE ? THEN 90
                WHEN subjects.name LIKE ? THEN 90
                WHEN boards.name LIKE ? THEN 75
                WHEN posts.chapter LIKE ? THEN 50
                WHEN posts.year LIKE ? THEN 90
                ELSE 0
            END)";

            // Add word-level scoring to the SQL string and the bindings array
            foreach ($words as $word) {
                $word = trim($word);
                if ($word) {
                    $w = "%{$word}%";
                    $scoreSql .= " + (CASE WHEN posts.article LIKE ? THEN 10 ELSE 0 END)";
                    $scoreSql .= " + (CASE WHEN institutions.name LIKE ? THEN 20 ELSE 0 END)";
                    $scoreSql .= " + (CASE WHEN subjects.name LIKE ? THEN 20 ELSE 0 END)";
                    $scoreSql .= " + (CASE WHEN boards.name LIKE ? THEN 20 ELSE 0 END)";
                    $scoreSql .= " + (CASE WHEN posts.chapter LIKE ? THEN 10 ELSE 0 END)";
                    $scoreSql .= " + (CASE WHEN posts.year LIKE ? THEN 20 ELSE 0 END)";
                    
                    // Push 6 bindings for each word
                    array_push($scoreBindings, $w, $w, $w, $w, $w, $w);
                }
            }

            // Apply the Score to the Select statement
            $query->selectRaw("{$scoreSql} as match_score", $scoreBindings);

            // 3. Apply the Search Filters (Where Clause)
            $query->where(function ($subQuery) use ($q, $words) {
                $full = "%{$q}%";
                $subQuery->where('posts.article', 'LIKE', $full)
                    ->orWhere('institutions.name', 'LIKE', $full)
                    ->orWhere('subjects.name', 'LIKE', $full)
                    ->orWhere('boards.name', 'LIKE', $full)
                    ->orWhere('posts.chapter', 'LIKE', $full)
                    ->orWhere('posts.year', 'LIKE', $full);

                foreach ($words as $word) {
                    $word = trim($word);
                    if ($word) {
                        $w = "%{$word}%";
                        $subQuery->orWhere('posts.article', 'LIKE', $w)
                            ->orWhere('institutions.name', 'LIKE', $w)
                            ->orWhere('subjects.name', 'LIKE', $w)
                            ->orWhere('boards.name', 'LIKE', $w)
                            ->orWhere('posts.chapter', 'LIKE', $w)
                            ->orWhere('posts.year', 'LIKE', $w);
                    }
                }
            });

            // 4. Order by Score, Viewed Status, and Date
            $query->orderByDesc('match_score')
                ->orderBy('was_viewed', 'asc')
                ->orderByDesc('posts.year')
                ->orderByDesc('posts.created_at');

        } else {
            // Simple Ordering if no search query
            $query->orderBy('was_viewed', 'asc')
                ->orderByDesc('posts.year')
                ->orderByDesc('posts.created_at');
        }

        $posts = $query->paginate($perPage)->withQueryString();

        return view('questions.index', compact('posts', 'q'));
    }



    public function list()
    {
        $posts = Post::all(); // or Question::all();
        return Inertia::render('Questions/Index', [
            'posts' => $posts
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
    
    /**
     * Display posts filtered by a specific subject (SEO Friendly).
     */
    public function exam($institutionSlug = null, $subjectSlug = null, $year = null)
    {
        $query = Post::query()
            ->with(['institution', 'subject', 'board'])
            ->orderByDesc('year')
            ->orderByDesc('created_at');
    
        // =====================
        // 1. NO INSTITUTION
        // =====================
        if (!$institutionSlug) {
            return view('questions.institution', [
                'institutions' => Institution::select('id', 'name', 'slug')->get(),
                'posts' => $query->paginate(10),
            ]);
        }
    
        $institution = Institution::where('slug', $institutionSlug)->firstOrFail();
        $query->where('institution_id', $institution->id);
    
        // =====================
        // 2. NO SUBJECT (Display Unique Subjects)
        // =====================
        if (!$subjectSlug) {
            $allSubjects = Subject::where('institution_id', $institution->id)->get();
    
            // ১ ১ম ও ২য় পত্র বাদ দিয়ে ইউনিক লিস্ট তৈরি
            $subjects = $allSubjects->unique(function ($item) {
                return trim(str_replace(['1st', '2nd'], '', $item->name));
            });
    
            return view('questions.subject', [
                'institution' => $institution,
                'subjects' => $subjects,
                'posts' => $query->paginate(10),
            ]);
        }
    
        // =====================
        // 3. SUBJECT (Generic vs Specific Logic)
        // =====================
        
        // চেক করা হচ্ছে স্লাগে '1st' বা '2nd' আছে কি না
        $isSpecific = str_contains($subjectSlug, '1st') || str_contains($subjectSlug, '2nd');
    
        if ($isSpecific) {
            // সরাসরি নির্দিষ্ট সাবজেক্টটি খুঁজে বের করা
            $subject = Subject::where('slug', $subjectSlug)
                ->where('institution_id', $institution->id)
                ->firstOrFail();
            
            $relatedSubjectIds = [$subject->id];
            $displayName = $subject->name;
        } else {
            // জেনেরিক নাম তৈরি করা (যেমন: 'bangla')
            $baseName = str_replace('-', ' ', $subjectSlug);
            
            // ওই নামের সাথে মিল আছে এমন সকল সাবজেক্ট আইডি (১ম ও ২য় পত্র)
            $relatedSubjectIds = Subject::where('institution_id', $institution->id)
                ->where('name', 'LIKE', $baseName . '%')
                ->pluck('id');
    
            if ($relatedSubjectIds->isEmpty()) {
                abort(404);
            }
    
            // ডিসপ্লের জন্য একটি ডিফল্ট অবজেক্ট নেওয়া
            $subject = Subject::whereIn('id', $relatedSubjectIds)->first();
            $displayName = ucwords($baseName);
        }
    
        $query->whereIn('subject_id', $relatedSubjectIds);
    
        // =====================
        // 4. YEAR LOGIC
        // =====================
        if (!$year) {
            return view('questions.year', [
                'institution' => $institution,
                'subject' => $subject,
                'subjectSlug' => $subjectSlug,
                'displayName' => $displayName,
                'years' => Post::where('institution_id', $institution->id)
                    ->whereIn('subject_id', $relatedSubjectIds)
                    ->distinct()
                    ->orderByDesc('year')
                    ->pluck('year'),
                'posts' => $query->paginate(10),
            ]);
        }
    
        // নির্দিষ্ট বছর ফিল্টার করা
        $query->where('year', $year);
    
        return view('questions.hierarchy', [
            'institution' => $institution,
            'subject' => $subject,
            'subjectSlug' => $subjectSlug,
            'displayName' => $displayName,
            'year' => $year,
            'posts' => $query->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request) 
    {
        // Extract data except the file input and tokens
        $data = $request->except(['_token', '_method', 'images']);
        $processedImages = []; // Array to track [ 'image1' => 'absolute/temp/path.webp' ]
    
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $targetFolder = public_html_path('images/questions');
            $converter = new Image2WebpService();
    
            // 1. Process only the first 4 images
            foreach (array_slice($files, 0, 4) as $index => $file) {
                $imageNumber = $index + 1; // Uniqueness suffix: 1, 2, 3, 4
                $dbField = 'image' . $imageNumber;
    
                // Generate a temporary base name (without ID yet)
                $baseName = question_image_basename($data);
                $extension = $file->getClientOriginalExtension();
                $tempFilename = $baseName . '.' . $extension;
    
                // 2. Move original file to target folder
                $file->move($targetFolder, $tempFilename);
                $fullPath = $targetFolder . DIRECTORY_SEPARATOR . $tempFilename;
    
                // 3. Convert to WebP using the 4th parameter ($imageNumber)
                // This will return something like: .../images/questions/basename-1.webp
                $tempWebpPath = $converter->convert($fullPath, 800, 80, $imageNumber);
                
                // Keep track of the temporary WebP path to rename it later with ID
                $processedImages[$dbField] = $tempWebpPath;
    
                // Set temporary path in data array for initial creation
                $data[$dbField] = ltrim(
                    str_replace(public_html_path(), '', $tempWebpPath),
                    '/'
                );
    
                // Clean up the original moved file (non-webp) if you don't want to keep it
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
        }
    
        // 4. Create the question record in the Database
        $question = Post::create($data);
    
        // 5. Rename images AFTER we have the Question ID for better SEO/Organization
        $finalPaths = [];
        foreach ($processedImages as $field => $oldWebpPath) {
            if ($oldWebpPath && file_exists($oldWebpPath)) {
                $pathInfo = pathinfo($oldWebpPath);
    
                // Define final name: basename-number-id.webp
                $finalFilename = $pathInfo['filename']
                    . '-' . $question->id 
                    . '.webp';
    
                $finalFullPath = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $finalFilename;
    
                // Perform the rename
                rename($oldWebpPath, $finalFullPath);
    
                // Prepare for batch update
                $finalPaths[$field] = ltrim(
                    str_replace(public_html_path(), '', $finalFullPath),
                    '/'
                );
            }
        }
        // 6. Final Update to DB with correct filenames containing the ID
        if (!empty($finalPaths)) {
            $question->update($finalPaths);
        }
    
        return redirect()
            ->route('questions.show', $question->id)
            ->with('success', 'Question created successfully with ' . count($processedImages) . ' images.');
    }

    public function upload(Request $request) 
    {
        $request->validate(['image' => 'required|image|max:2048']);
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Use your custom public_html path
            $targetFolder = public_html_path('images/articles');
            
            if (!file_exists($targetFolder)) {
                mkdir($targetFolder, 0755, true);
            }

            $baseName = 'article_' . time() . '_' . uniqid();
            $extension = $file->getClientOriginalExtension();
            $tempFilename = $baseName . '.' . $extension;

            $file->move($targetFolder, $tempFilename);
            $fullPath = $targetFolder . DIRECTORY_SEPARATOR . $tempFilename;

            $converter = new Image2WebpService();
            
            // Pass 0 to keep original width, 80 for quality
            $webpFullPath = $converter->convert($fullPath, 0, 80);

            // Clean up original if it wasn't already webp
            if (file_exists($fullPath) && $extension !== 'webp') {
                unlink($fullPath);
            }

            // Generate the URL for the frontend
            $relativeUrl = ltrim(str_replace(public_html_path(), '', $webpFullPath), '/');

            return response()->json([
                'url' => asset($relativeUrl)
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($questionId, $slug = null)
    {
        // Eager load relationships + nested comment authors
        $post = Post::with([
            'institution', 
            'subject', 
            'board', 
            'comments.user' // Eager load comments AND the user who wrote them
        ])->findOrFail($questionId);

        $q_meta = question_meta_text($post);
        $realSlug = url_slug($post->article, $q_meta);

        if (!$slug || $slug !== $realSlug) {
            return redirect()->route('questions.show', [
                'question' => $post->id,
                'slug' => $realSlug
            ]);
        }
        return view('questions.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);

        return Inertia::render('Questions/Edit', [
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        // Exclude _token and _method fields
        $data = $request->except(['_token', '_method']);

        $post->update($data);

        return redirect()->route('questions.show', $post->id)
                        ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('questions.list')
                         ->with('success', 'Question deleted successfully.');
    }

    public function search(Request $request)
    {
        // 0. Redirect to clean URL if needed
        $cleanQuery = array_filter(
            $request->query(),
            fn ($value) => $value !== null && $value !== ''
        );

        if ($cleanQuery !== $request->query()) {
            return redirect()
                ->route('search', $cleanQuery)
                ->setStatusCode(301);
        }

        // 1. Fetch available filter options
        $filters = $this->getAvailableFilters();

        // 2. Build the query
        $query = Post::query();

        // --- VIEWED QUESTIONS LOGIC START ---
        $user = auth()->user();
        if ($user) {
            $query->leftJoin('viewed_posts', function($join) use ($user) {
                $join->on('posts.id', '=', 'viewed_posts.post_id')
                    ->where('viewed_posts.user_id', '=', $user->id);
            })
            ->selectRaw('posts.*, CASE WHEN viewed_posts.id IS NULL THEN 0 ELSE 1 END as was_viewed');
        } else {
            $query->selectRaw('posts.*, 0 as was_viewed');
        }
        // --- VIEWED QUESTIONS LOGIC END ---

        if ($request->filled('institution_id')) {
            $query->where('posts.institution_id', $request->institution_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('posts.subject_id', $request->subject_id);
        }

        if ($request->filled('board_id')) {
            $query->where('posts.board_id', $request->board_id);
        }

        if ($request->filled('year')) {
            $query->where('posts.year', $request->year);
        }

        if ($request->filled('class')) {
            $query->where('posts.class', $request->class);
        }

        // Update ordering to prioritize 'was_viewed' (0 comes before 1)
        $posts = $query->with(['institution', 'subject', 'board'])
            ->orderBy('was_viewed', 'asc') // Priority 1: Unseen questions
            ->orderBy('posts.year', 'desc') // Priority 2: Newest Year
            ->orderBy('posts.created_at', 'desc') // Priority 3: Newest Database entry
            ->paginate(10)
            ->withQueryString();

        $currentParams = array_merge($request->all(), [
            'institution_name' => $request->filled('institution_id')
                ? Institution::find($request->institution_id)?->name
                : null,

            'subject_name' => $request->filled('subject_id')
                ? Subject::find($request->subject_id)?->name
                : null,

            'board_name' => $request->filled('board_id')
                ? Board::find($request->board_id)?->name
                : null,
        ]);

        return view('pages.search', [
            'initialFilters' => $filters,
            'posts' => $posts,
            'currentParams' => $currentParams,
        ]);
    }


    /**
     * Internal helper to fetch all available filter options from the Post model.
     */
    protected function getAvailableFilters()
{
    return [
        'institutions' => Institution::orderBy('name')->get(['id', 'name']),
        'boards'       => Board::orderBy('name')->get(),
        'years'        => Post::distinct()->pluck('year')->filter()->sortDesc()->values()->toArray(),
        'classes'      => Post::distinct()->pluck('class')->filter()->sort()->values()->toArray(),
    ];
}


    /**
     * API endpoint to fetch distinct subjects based on a selected Institution ID.
     */
    public function getSubjectsByInstitution(Request $request) {
        if(!$request->institution_id)
            return response()->json([]);
        
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
}