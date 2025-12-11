<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use App\Models\Post;

class SitemapController extends Controller
{
    public function generate()
    {
        $sitemapPath = public_path('sitemap.xml');

        // If file exists, simply return it (no regeneration)
        if (File::exists($sitemapPath)) {
            return response()->file($sitemapPath, [
                'Content-Type' => 'application/xml'
            ]);
        }

        // Fetch posts (customize as you want)
        $posts = Post::latest()->get();

        // Generate XML
        $xml = view('sitemap', compact('posts'))->render();

        // Save XML to public folder
        File::put($sitemapPath, $xml);

        // Return the file immediately
        return response()->file($sitemapPath, [
            'Content-Type' => 'application/xml'
        ]);
    }
}