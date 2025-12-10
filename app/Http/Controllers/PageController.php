<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function home()
    {
        return view('home.index', [
            'title' => 'Home - ICT4Today',
            'description' => 'ICT tutorials, Laravel, Vue, Tailwind tips, and public exam questions.'
        ]);
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
}