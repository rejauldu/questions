<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Institution;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        $user = auth()->user();
        // Fetch institutions so the user can change their target exam if needed
        $institutions = Institution::all(); 
        
        return view('profile.edit', compact('user', 'institutions'));
    }

    public function show()
    {
        $user = Auth::user();
        
        $institution = $user->institution;
        $recommendedSubjects = $institution ? $institution->subjects()->limit(4)->get() : [];

        $recentAttempts = $user->examAttempts()
                            ->with('subject')
                            ->latest()
                            ->limit(5)
                            ->get();

        // ADD THIS: Fetch bookmarked questions
        $bookmarks = $user->bookmarks()
                        ->with('post')
                        ->latest()
                        ->limit(10) 
                        ->get();

        return view('profile.show', compact('user', 'recommendedSubjects', 'recentAttempts', 'bookmarks'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institution_id' => 'required|exists:institutions,id',
            'hsc_group' => 'nullable|string|in:Science,Arts,Commerce',
        ]);

        $user->update($validated);

        return redirect()->route('profile.show')->with('status', 'Profile updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function getStatus()
    {
        $user = auth()->user();

        return response()->json([
            'auth' => (bool)$user,
            'csrf' => csrf_token(),
            'user' => $user ? [
                'name'    => $user->name,
                'initial' => substr($user->name, 0, 1),
                'role'    => $user->role, // Ensure your 'users' table has a 'role' column
            ] : null
        ]);
    }
}
