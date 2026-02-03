<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ViewedPost; // এডেড
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // --- গেস্ট ডাটা মার্জ লজিক শুরু ---
        $uuid = $request->cookie('examdao_uuid');
        $user = Auth::user();

        if ($uuid && $user) {
            // গেস্ট হিসেবে পড়া পোস্টগুলো খুঁজে বের করা
            $guestViews = ViewedPost::where('visitor_uuid', $uuid)->get();

            foreach ($guestViews as $view) {
                // ইউজারের অ্যাকাউন্টে এই পোস্টটি অলরেডি 'পড়া' হিসেবে আছে কি না চেক করা
                $exists = ViewedPost::where('user_id', $user->id)
                    ->where('post_id', $view->post_id)
                    ->exists();

                if (!$exists) {
                    // না থাকলে গেস্ট এন্ট্রিকে ইউজারের সাথে ট্যাগ করা
                    $view->update([
                        'user_id' => $user->id,
                        'visitor_uuid' => null
                    ]);
                } else {
                    // ডুপ্লিকেট হলে গেস্ট এন্ট্রি মুছে ফেলা
                    $view->delete();
                }
            }
        }
        // --- গেস্ট ডাটা মার্জ লজিক শেষ ---

        return redirect()->intended(route('profile.show', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}