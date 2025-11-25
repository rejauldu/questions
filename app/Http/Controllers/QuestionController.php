<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;
use App\Models\Institution;

class QuestionController extends Controller
{
    
    
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $posts = Post::all();
        return view("questions.index", compact("posts"));
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
        return Inertia::render('Questions/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        $post = Post::create($data);

        return redirect()->route('questions.show', $post->id)
                         ->with('success', 'Question created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);

        return view("questions.show", compact("post"));
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
        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
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
        $posts = $query->orderBy('subject')
                      ->orderBy('topic')
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

        $subjects = Post::where('institution_id', $request->institution_id)
            ->distinct()
            ->pluck('subject')
            ->filter()
            ->sort()
            ->values();

        return response()->json($subjects);
    }
}