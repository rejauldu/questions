<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
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
        $perPage = 10;

        $query = \App\Models\Post::query()->with(['institution', 'subject'])
                ->join('institutions', 'institutions.id', '=', 'posts.institution_id')
                ->join('subjects', 'subjects.id', '=', 'posts.subject_id');

        if ($q) {
            $words = preg_split('/\s+/', $q); // split by spaces
            $bindings = [];

            // Direct full query match score
            $scoreSql = "(CASE 
                WHEN posts.article LIKE ? THEN 70
                WHEN institutions.name LIKE ? THEN 90
                WHEN subjects.name LIKE ? THEN 80
                ELSE 0
            END)";

            $bindings[] = "%{$q}%";
            $bindings[] = "%{$q}%";
            $bindings[] = "%{$q}%";

            // Add word match score dynamically
            foreach ($words as $word) {
                $word = trim($word);
                if ($word) {
                    $scoreSql .= " + (CASE WHEN posts.article LIKE ? THEN 10 ELSE 0 END)";
                    $bindings[] = "%{$word}%";

                    $scoreSql .= " + (CASE WHEN institutions.name LIKE ? THEN 15 ELSE 0 END)";
                    $bindings[] = "%{$word}%";

                    $scoreSql .= " + (CASE WHEN subjects.name LIKE ? THEN 15 ELSE 0 END)";
                    $bindings[] = "%{$word}%";
                }
            }

            $query->selectRaw("posts.*, {$scoreSql} as match_score", $bindings)
                ->where(function ($subQuery) use ($q, $words) {
                    $subQuery->where('posts.article', 'LIKE', "%{$q}%")
                            ->orWhere('institutions.name', 'LIKE', "%{$q}%")
                            ->orWhere('subjects.name', 'LIKE', "%{$q}%");

                    // Add word-level matching
                    foreach ($words as $word) {
                        $word = trim($word);
                        if ($word) {
                            $subQuery->orWhere('posts.article', 'LIKE', "%{$word}%")
                                    ->orWhere('institutions.name', 'LIKE', "%{$word}%")
                                    ->orWhere('subjects.name', 'LIKE', "%{$word}%");
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
        // Get institutions
        $institutions = Institution::select('id','name')->get();

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
            'years' => $years,
            'classes' => $classes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        
        if ($request->hasFile('url')) {
            $file = $request->file('url');
            $path = $file->store('questions', 'public'); // store in storage/app/public/questions
            
            $fullPath = storage_path('app/public/' . $path);
            $converter = new Image2WebpService();
            $webpPath = $converter->convert($fullPath, 800, 80);

            // Convert the WebP full path back to a relative path for public access
            $relativePath = str_replace(storage_path('app/public') . '/', '', $webpPath);

            $data['url'] = $relativePath; // store relative path in DB
        }

        $post = Post::create($data);

        return redirect()->route('questions.show', $post->id)
                        ->with('success', 'Question created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($questionId, $slug = null)
    {
        $post = Post::findOrFail($questionId);

        $q_meta = question_meta_text($post);

        // Optionally, generate the slug
        $realSlug = url_slug($post->article, $q_meta);

        // Redirect if slug is missing or incorrect
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
        // 1. Fetch available filter options
        $filters = $this->getAvailableFilters();

        // 2. Build the query based on request parameters
        $query = Post::query();

        // Apply filters
        if ($request->filled('institution_id')) {
            $query->where('institution_id', $request->institution_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('topic')) {
            $query->where('topic', $request->topic);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }
        if ($request->filled('board')) {
            $query->where('board', $request->board);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Apply full-text search term
        if ($request->filled('search_term')) {
            $searchTerm = $request->search_term;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('article', 'like', "%{$searchTerm}%")
                  ->orWhere('a', 'like', "%{$searchTerm}%")
                  ->orWhere('b', 'like', "%{$searchTerm}%")
                  ->orWhere('c', 'like', "%{$searchTerm}%")
                  ->orWhere('d', 'like', "%{$searchTerm}%");
            });
        }
        
        // Results are limited to 10 as per request
        $perPage = 10; 

        // Fetch results with pagination
        $posts = $query->with(['institution', 'subject'])
            ->orderBy('year', 'desc')
            ->orderBy(
                Subject::select('name')->whereColumn('subjects.id', 'posts.subject_id')
            )
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // 3. Return the Blade view with data
        return view('pages.search', [
            'initialFilters' => $filters,
            'posts' => $posts,
            // Add institution name to currentParams if an ID is present, for pre-filling the text input
            'currentParams' => array_merge($request->all(), [
                'institution_name' => $request->filled('institution_id') 
                    ? Institution::find($request->institution_id)?->name 
                    : null
            ]),
        ]);
    }


    /**
     * Internal helper to fetch all available filter options from the Post model.
     */
    protected function getAvailableFilters()
    {
        // Fetch all institutions for autocomplete list
        $institutions = Institution::all(['id', 'name']);

        // Fetch distinct values for key filter fields (excluding Subject)
        $topics = Post::distinct()->pluck('topic')->filter()->sort()->values()->toArray();
        $years = Post::distinct()->pluck('year')->filter()->sort()->values()->toArray();
        rsort($years);

        return [
            'institutions' => $institutions,
            'topics' => $topics,
            'years' => $years,
            'classes' => Post::distinct()->pluck('class')->filter()->sort()->values()->toArray(),
            'categories' => Post::distinct()->pluck('category')->filter()->sort()->values()->toArray(),
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