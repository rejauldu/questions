<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => ['required', 'string', 'max:500'],
            // Add validation for 'user_name' if allowing guests
        ]);

        $commentData = [
            'post_id' => $post->id,
            'body' => $request->input('body'),
        ];

        // Handle logged-in vs. guest commenting
        $commentData['user_id'] = Auth::id();

        Comment::create($commentData);

        return back()->with('success', 'Comment added successfully!');
    }
}