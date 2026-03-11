<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class GuideController extends Controller
{
    public function show($slug)
    {
        // 1. Check if the blade file exists in the 'guide' folder
        if (!View::exists("hsc.guide.{$slug}")) {
            abort(404, "Guide topic not found.");
        }

        // 2. Fetch 'Live Stats' (Simulated logic for examdao.com)
        // In a real app, you might fetch this from Redis or a Cache
        $liveCount = rand(450, 600); 

        // 3. Define specific data based on the slug (Optional)
        $data = [
            'live_count' => $liveCount,
            'slug' => $slug,
            'page_title' => ucfirst(str_replace('-', ' ', $slug)) . " | HSC 2026 Guide"
        ];

        // 4. Return the view dynamically
        return view("hsc.guide.{$slug}", $data);
    }
}