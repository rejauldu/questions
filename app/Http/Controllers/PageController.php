<?php

namespace App\Http\Controllers;

use App\Models\{Institution, Subject};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Home Page: Optimized with Caching and Column Selection.
     */
    public function home()
    {
        // Cache the entire data array for 24 hours (86400 seconds)
        $data = Cache::remember('home_page_data', 86400, function () {
            return [
                'institutions' => Institution::where('status', 1)
                    ->orderBy('name')
                    ->get(['id', 'name', 'slug']),

                'bcsSubjects' => Subject::where('institution_id', 4)
                    ->where('status', 1)
                    ->get(['id', 'name', 'slug']),

                'hscSubjects' => Subject::where('institution_id', 2)
                    ->where('status', 1)
                    ->get(['id', 'name', 'slug']),
            ];
        });

        return view('home.index', $data);
    }

    /**
     * Inertia Render Methods
     */
    public function chatbot()
    {
        return Inertia::render('Chatbot/Index', [], 'chat_layout');
    }

    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }

    /**
     * Static Pages
     */
    public function contact()
    {
        return view('pages.contact');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function about()
    {
        return view('pages.about');
    }
}