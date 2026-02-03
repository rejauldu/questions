<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ViewedPost; // এডেড
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * Updated to return a Blade View.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:15', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // --- গেস্ট ডাটা মার্জ লজিক শুরু ---
        $uuid = $request->cookie('examdao_uuid');

        if ($uuid) {
            // গেস্ট হিসেবে পড়া পোস্টগুলো খুঁজে বের করা
            $guestViews = ViewedPost::where('visitor_uuid', $uuid)->get();

            foreach ($guestViews as $view) {
                // যেহেতু এটি নতুন রেজিস্ট্রেশন, ডুপ্লিকেট থাকার সম্ভাবনা নেই, 
                // তবুও সেফটি চেক রাখা ভালো
                $exists = ViewedPost::where('user_id', $user->id)
                    ->where('post_id', $view->post_id)
                    ->exists();

                if (!$exists) {
                    $view->update([
                        'user_id' => $user->id,
                        'visitor_uuid' => null
                    ]);
                } else {
                    $view->delete();
                }
            }
        }
        // --- গেস্ট ডাটা মার্জ লজিক শেষ ---

        return redirect(route('profile.show', absolute: false));
    }
}