<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Institution;
use App\Models\Subject;

class PageController extends Controller
{
    public function home()
    {
        $institutions = Institution::orderBy('name')->where('status', 1)->get();

        // Fetching subjects grouped by type (adjust the logic based on your DB schema)
        $bcsSubjects = Subject::where('institution_id', 4)->where('status', 1)->get();
        $hscSubjects = Subject::where('institution_id', 2)->whereIn('name', ['Bangla 1st', 'Bangla 2nd', 'English 1st', 'English 2nd', 'ICT'])->where('status', 1)->get();

        return view('home.index', compact('institutions', 'bcsSubjects', 'hscSubjects'));
    }

    public function contact()
    {
        return view('pages.contact');
    }
    public function chatbot()
    {
        return Inertia::render('Chatbot/Index', [], 'chat_layout');
    }
    public function dashboard()
    {
        return Inertia::render('Dashboard');
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