<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Board;
use App\Models\Post;
use App\Models\Institution;
use App\Models\Subject;
use App\Services\Image2WebpService;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'update']);
    }
    
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $q = trim($request->input('q'));

        // Only redirect if query string 'q' exists but is empty
        if ($request->has('q') && $q === '') {
            return redirect()->route('questions.index');
        }
        $perPage = 10;
    
        $query = Post::query()
            ->with(['institution', 'subject', 'board'])
            ->join('institutions', 'institutions.id', '=', 'posts.institution_id')
            ->join('subjects', 'subjects.id', '=', 'posts.subject_id')
            ->join('boards', 'boards.id', '=', 'posts.board_id');
    
        if ($q) {
            $words = preg_split('/\s+/', $q);
            $bindings = [];
    
            // Full query match score
            $scoreSql = "(CASE 
                WHEN posts.article LIKE ? THEN 70
                WHEN institutions.name LIKE ? THEN 90
                WHEN subjects.name LIKE ? THEN 80
                WHEN boards.name LIKE ? THEN 85
                ELSE 0
            END)";
    
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
                }
            }
    
            $query->selectRaw("posts.*, {$scoreSql} as match_score", $bindings)
                ->where(function ($subQuery) use ($q, $words) {
                    $subQuery->where('posts.article', 'LIKE', "%{$q}%")
                        ->orWhere('institutions.name', 'LIKE', "%{$q}%")
                        ->orWhere('subjects.name', 'LIKE', "%{$q}%")
                        ->orWhere('boards.name', 'LIKE', "%{$q}%");
    
                    foreach ($words as $word) {
                        $word = trim($word);
                        if ($word) {
                            $subQuery->orWhere('posts.article', 'LIKE', "%{$word}%")
                                ->orWhere('institutions.name', 'LIKE', "%{$word}%")
                                ->orWhere('subjects.name', 'LIKE', "%{$word}%")
                                ->orWhere('boards.name', 'LIKE', "%{$word}%");
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
        $years = [];
        for ($i = 0; $i < 6; $i++) {
            $years[] = $currentYear - $i;
        }

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
    public function store(Request $request) {
        $data = $request->except(['_token', '_method']);
        // $data["subject_id"] = 50;
        // $data["board_id"] = 6;
        $tempWebpPath = null;
    
        if ($request->hasFile('url')) {
            $file = $request->file('url');
    
            $targetFolder = public_html_path('images/questions');
    
            // 1. Base filename (NO ID yet)
            $baseName = question_image_basename($data);
    
            $extension = $file->getClientOriginalExtension();
            $tempFilename = $baseName . '.' . $extension;
    
            // 2. Move original
            $file->move($targetFolder, $tempFilename);
    
            $fullPath = $targetFolder . DIRECTORY_SEPARATOR . $tempFilename;
    
            // 3. Convert to WebP
            $converter = new Image2WebpService();
            $tempWebpPath = $converter->convert($fullPath, 800, 50);
            
            // Temporarily store URL (will update after ID exists)
            $data['url'] = ltrim(
                str_replace(public_html_path(), '', $tempWebpPath),
                '/'
            );
        }
    
        // 4. Create question row
        $question = Post::create($data);
    
        // 5. Rename image AFTER we have ID
        if ($tempWebpPath && file_exists($tempWebpPath)) {
    
            $pathInfo = pathinfo($tempWebpPath);
    
            $newFilename = $pathInfo['filename']
                . '-' . $question->id
                . '.webp';
    
            $newFullPath = $pathInfo['dirname']
                . DIRECTORY_SEPARATOR
                . $newFilename;
    
            rename($tempWebpPath, $newFullPath);
    
            // 6. Update DB with final filename
            $question->update([
                'url' => ltrim(
                    str_replace(public_html_path(), '', $newFullPath),
                    '/'
                )
            ]);
        }
    
        return redirect()
            ->route('questions.show', $question->id)
            ->with('success', 'Question created successfully.');
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
            ->orderBy(
                Subject::select('name')
                    ->whereColumn('subjects.id', 'posts.subject_id')
            )
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
    public function getSubjectsByInstitution(Request $request)
    {
        $request->validate([
            'institution_id' => 'required|exists:institutions,id',
        ]);

        $subjects = Subject::whereHas('posts', function($q) use ($request) {
                $q->where('institution_id', $request->institution_id);
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($subjects);
    }
}