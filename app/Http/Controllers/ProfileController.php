<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Redirect, Cache, Redis};
use App\Models\Institution;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Cache Institutions for 24 hours. 
     * They rarely change, so don't hit the DB every time.
     */
    public function edit()
    {
        $user = auth()->user();
        
        $institutions = Cache::remember('all_institutions', 86400, function () {
            return Institution::all();
        });
        
        return view('profile.edit', compact('user', 'institutions'));
    }

    /**
     * Profile pages are usually private. We use a user-specific cache key.
     */
    public function show()
    {
        $user = Auth::user();
        $cacheKey = "user_profile_data_{$user->id}";

        // Cache the complex joins/queries for 10 minutes
        $data = Cache::remember($cacheKey, 600, function () use ($user) {
            $institution = $user->institution;
            
            return [
                'recommendedSubjects' => $institution ? $institution->subjects()->limit(4)->get() : [],
                'recentAttempts' => $user->examAttempts()->with('subject')->latest()->limit(5)->get(),
                'bookmarks' => $user->bookmarks()->with('post')->latest()->limit(10)->get(),
            ];
        });

        return view('profile.show', array_merge(['user' => $user], $data));
    }

    /**
     * On update, we MUST delete the old Redis cache keys.
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

        // CLEAR CACHE: So the user sees their new data immediately
        Cache::forget("user_profile_data_{$user->id}");
        Cache::forget("user_stat_{$user->id}"); // Clear getStatus cache too

        return redirect()->route('profile.show')->with('status', 'Profile updated successfully!');
    }

    /**
     * Optimized getStatus using the logic we discussed earlier.
     */
    public function getStatus()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['auth' => false, 'csrf' => csrf_token()]);
        }

        // Extremely fast lookup for repeated calls
        $userData = Cache::remember("user_stat_{$user->id}", 3600, function () use ($user) {
            return [
                'name'    => $user->name,
                'initial' => $user->initial, // Assumes attribute in model
                'role'    => $user->role,
            ];
        });

        return response()->json([
            'auth' => true,
            'csrf' => csrf_token(),
            'user' => $userData
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        
        // Clean up Redis before deleting user
        Cache::forget("user_profile_data_{$user->id}");
        Cache::forget("user_stat_{$user->id}");

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}