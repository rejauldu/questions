<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use App\Models\Post;
use App\Models\Subject;
use App\Models\Institution;

class SitemapController extends Controller
{
    public function generate()
    {
        $sitemapPath = public_html_path('sitemap.xml');

        // Delete existing sitemap if exists
        if (File::exists($sitemapPath)) {
            File::delete($sitemapPath);
        }

        // Fetch posts, subjects, institutions
        $posts = Post::latest()->get();
        $subjects = Subject::where('status',1)->get();
        $institutions = Institution::all();

        // Generate XML using a Blade view
        $xml = view('sitemap', compact('posts', 'subjects', 'institutions'))->render();

        // Save XML to public folder
        File::put($sitemapPath, $xml);

        // Return sitemap immediately
        return response()->file($sitemapPath, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
