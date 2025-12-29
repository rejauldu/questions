<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Institution;

class PageController extends Controller
{
    public function home()
    {
        $institutions = Institution::orderBy('name')->get();
        return view('home.index', compact('institutions'));
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