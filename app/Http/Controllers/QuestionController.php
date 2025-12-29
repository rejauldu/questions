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

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'update']);
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
    
        if ($q) {
            $words = preg_split('/\s+/', $q);
            $bindings = [];
    
            // Base relevance scoring
            $scoreSql = "(CASE
                WHEN posts.article LIKE ? THEN 70
                WHEN institutions.name LIKE ? THEN 90
                WHEN subjects.name LIKE ? THEN 80
                WHEN boards.name LIKE ? THEN 85
                WHEN posts.chapter LIKE ? THEN 75
                ELSE 0
            END)";
    
            $bindings[] = "%{$q}%";
            $bindings[] = "%{$q}%";
            $bindings[] = "%{$q}%";
            $bindings[] = "%{$q}%";
            $bindings[] = "%{$q}%";
    
            // Word-level scoring
            foreach ($words as $word) {
                $word = trim($word);
                if ($word) {
                    $scoreSql .= " + (CASE WHEN posts.article LIKE ? THEN 10 ELSE 0 END)";
                    $bindings[] = "%{$word}%";
    
                    $scoreSql .= " + (CASE WHEN institutions.name LIKE ? THEN 15 ELSE 0 END)";
                    $bindings[] = "%{$word}%";
    
                    $scoreSql .= " + (CASE WHEN subjects.name LIKE ? THEN 15 ELSE 0 END)";
                    $bindings[] = "%{$word}%";
    
                    $scoreSql .= " + (CASE WHEN boards.name LIKE ? THEN 15 ELSE 0 END)";
                    $bindings[] = "%{$word}%";
    
                    $scoreSql .= " + (CASE WHEN posts.chapter LIKE ? THEN 15 ELSE 0 END)";
                    $bindings[] = "%{$word}%";
                }
            }
    
            $query->selectRaw("posts.*, {$scoreSql} as match_score", $bindings)
                ->where(function ($subQuery) use ($q, $words) {
    
                    $subQuery->where('posts.article', 'LIKE', "%{$q}%")
                        ->orWhere('institutions.name', 'LIKE', "%{$q}%")
                        ->orWhere('subjects.name', 'LIKE', "%{$q}%")
                        ->orWhere('boards.name', 'LIKE', "%{$q}%")
                        ->orWhere('posts.chapter', 'LIKE', "%{$q}%");
    
                    foreach ($words as $word) {
                        $word = trim($word);
                        if ($word) {
                            $subQuery->orWhere('posts.article', 'LIKE', "%{$word}%")
                                ->orWhere('institutions.name', 'LIKE', "%{$word}%")
                                ->orWhere('subjects.name', 'LIKE', "%{$word}%")
                                ->orWhere('boards.name', 'LIKE', "%{$word}%")
                                ->orWhere('posts.chapter', 'LIKE', "%{$word}%");
                        }
                    }
                })
                ->orderByDesc('match_score')
                ->orderByDesc('posts.year')
                ->orderByDesc('posts.created_at');
    
        } else {
            $query->select('posts.*')
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
    
        // =====================
        // 2. INSTITUTION
        // =====================
        $institution = Institution::where('slug', $institutionSlug)->firstOrFail();
        $query->where('institution_id', $institution->id);
    
        if (!$subjectSlug) {
            return view('questions.subject', [
                'institution' => $institution,
                'subjects' => Subject::where('institution_id', $institution->id)
                    ->select('id', 'name', 'slug')
                    ->get(),
                'posts' => $query->paginate(10),
            ]);
        }
    
        // =====================
        // 3. SUBJECT
        // =====================
        $subject = Subject::where('slug', $subjectSlug)
            ->where('institution_id', $institution->id)
            ->firstOrFail();
    
        $query->where('subject_id', $subject->id);
    
        if (!$year) {
            return view('questions.year', [
                'institution' => $institution,
                'subject' => $subject,
                'years' => Post::where('institution_id', $institution->id)
                    ->where('subject_id', $subject->id)
                    ->distinct()
                    ->orderByDesc('year')
                    ->pluck('year'),
                'posts' => $query->paginate(10),
            ]);
        }
    
        // =====================
        // 4. YEAR
        // =====================
        $query->where('year', $year);
    
        return view('questions.hierarchy', [
            'institution' => $institution,
            'subject' => $subject,
            'year' => $year,
            'posts' => $query->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Institutions
        $institutions = Institution::orderBy('name')->get(['id', 'name']);

        // Boards
        $boards = Board::orderBy('name')->get(['id', 'name']);

        // Years: last 6 years including current
        $currentYear = date('Y');
        $years = range(date('Y'), date('Y')-5);

        // Classes dropdown
        $classes = [
            ['value' => 1, 'text' => '1st Year'],
            ['value' => 2, 'text' => '2nd Year'],
            ['value' => 3, 'text' => '3rd Year'],
            ['value' => 4, 'text' => '4th Year'],
        ];

        return Inertia::render('Questions/Create', [
            'institutions' => $institutions,
            'boards' => $boards,   // ✅ added
            'years' => $years,
            'classes' => $classes,
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
    
        if ($request->filled('institution_id')) {
            $query->where('institution_id', $request->institution_id);
        }
    
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
    
        if ($request->filled('board_id')) {
            $query->where('board_id', $request->board_id);
        }
    
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
    
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }
    
        $posts = $query->with(['institution', 'subject', 'board'])
            ->orderBy('year', 'desc')
            ->orderBy('created_at', 'desc')
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