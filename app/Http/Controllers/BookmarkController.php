<?php
namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        // Fetch bookmarks but extract the 'post' from each one
        $posts = auth()->user()->bookmarks()
            ->with('post') // Eager load the question content
            ->latest()
            ->paginate(15)
            ->through(fn ($bookmark) => $bookmark->post); // This is the magic line

        return view('bookmarks.index', compact('posts'));
    }
    public function toggle(Request $request)
    {
        $request->validate(['post_id' => 'required|exists:posts,id']);

        $userId = Auth::id();
        $postId = $request->post_id;

        // Check if bookmark exists
        $bookmark = Bookmark::where('user_id', $userId)
                            ->where('post_id', $postId)
                            ->first();

        if ($bookmark) {
            $bookmark->delete();
            $status = 'removed';
            $message = 'Removed from saved items.';
        } else {
            Bookmark::create([
                'user_id' => $userId,
                'post_id' => $postId
            ]);
            $status = 'added';
            $message = 'Question saved successfully!';
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => $status, 'message' => $message]);
        }

        return back()->with('status', $message);
    }
}