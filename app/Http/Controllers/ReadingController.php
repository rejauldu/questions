<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\ViewedPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReadingController extends Controller
{
    public function show($institution_slug, $subject_slug, $id)
    {
        $user = auth()->user();
        $q = Post::with(['institution', 'subject', 'board'])->findOrFail($id);
        
        // Base scope for the specific Subject/Institution/Category
        $baseScope = Post::where('institution_id', $q->institution_id)
                        ->where('subject_id', $q->subject_id)
                        ->where('category', $q->category);

        // Get viewed data
        $viewedData = $user 
            ? ViewedPost::where('user_id', $user->id)->pluck('viewed_at', 'post_id')->toArray() 
            : [];
        $viewedIds = array_keys($viewedData);

        // --- Logic to find the NEXT question (The Priority Ladder) ---

        $next = null;

        // 1. Same Chapter + Unread (Only if chapter is valid)
        if ($q->chapter && $q->chapter !== 'None') {
            $next = (clone $baseScope)
                        ->where('chapter', $q->chapter)
                        ->where('id', '>', $q->id)
                        ->whereNotIn('id', $viewedIds)
                        ->orderBy('id', 'asc')
                        ->first();
        }

        // 2. Different Chapter + Unread
        if (!$next) {
            $next = (clone $baseScope)
                        ->whereNotIn('id', $viewedIds)
                        ->where('id', '>', $q->id) // Try to stay moving forward in ID
                        ->orderBy('id', 'asc')
                        ->first();
        }

        // 3. Any Chapter + Unread (Absolute first unread in subject)
        if (!$next) {
            $next = (clone $baseScope)
                        ->whereNotIn('id', $viewedIds)
                        ->orderBy('id', 'asc')
                        ->first();
        }

        // 4. Same Chapter + Read (Oldest viewed first to refresh memory)
        if (!$next && $user) {
            $next = (clone $baseScope)
                        ->join('viewed_posts', 'posts.id', '=', 'viewed_posts.post_id')
                        ->where('viewed_posts.user_id', $user->id)
                        ->where('posts.chapter', $q->chapter)
                        ->where('posts.id', '!=', $q->id)
                        ->orderBy('viewed_posts.viewed_at', 'asc')
                        ->select('posts.*')
                        ->first();
        }

        // 5. Global Fallback (Any viewed, oldest first)
        if (!$next && $user) {
            $next = (clone $baseScope)
                        ->join('viewed_posts', 'posts.id', '=', 'viewed_posts.post_id')
                        ->where('viewed_posts.user_id', $user->id)
                        ->where('posts.id', '!=', $q->id)
                        ->orderBy('viewed_posts.viewed_at', 'asc')
                        ->select('posts.*')
                        ->first();
        }

        // 6. Absolute Final Fallback
        if (!$next) {
            $next = (clone $baseScope)->where('id', '!=', $q->id)->first() ?? $q;
        }

        // --- Navigation URLs ---
        $prev = (clone $baseScope)->where('id', '<', $q->id)->orderBy('id', 'desc')->first() 
                ?? (clone $baseScope)->orderBy('id', 'desc')->first();

        $nextUrl = route('reading.mode', [$institution_slug, $subject_slug, $next->id, url_slug($next->article, question_meta_text($next))]);
        $prevUrl = route('reading.mode', [$institution_slug, $subject_slug, $prev->id, url_slug($prev->article, question_meta_text($prev))]);

        return view('questions.reading-mode', [
            'q' => $q,
            'nextUrl' => $nextUrl,
            'prevUrl' => $prevUrl,
            'isRead' => isset($viewedData[$q->id]),
            'title' => "Solution: " . Str::limit(strip_tags($q->article), 60),
        ]);
    }

    public function trackView(Request $request)
    {
        if (!auth()->check()) return response()->json(['error' => 'Unauthenticated'], 401);

        // Standard Eloquent model updateOrInsert
        ViewedPost::updateOrInsert(
            ['user_id' => auth()->id(), 'post_id' => $request->post_id],
            ['viewed_at' => now()]
        );

        return response()->json(['status' => 'tracked']);
    }
}