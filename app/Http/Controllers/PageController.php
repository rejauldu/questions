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

    public function about()
    {
        return view('pages.about', [
            'title' => 'About Us - ICT4Today',
            'description' => 'Learn about ICT4Today and our mission.'
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'title' => 'Contact Us - ICT4Today',
            'description' => 'Contact ICT4Today for questions and support.'
        ]);
    }
    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }
}