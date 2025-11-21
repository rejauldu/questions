<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post; // or Question if your model is Question

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
}