<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Subject;
use Illuminate\Http\Request;

class BcsController extends Controller
{
    /**
     * BCS Landing Page (Index)
     * URL: /bcs
     */
    public function index()
    {
        // Based on your preference for active learning and 'BCS Cadre' advantage
        return view('bcs.index');
    }

    /**
     * Handle BCS Question Views and Year/Category filtering
     * URL: /bcs/{year?}/{category?}
     */
    public function bcs($year = null, $category = null)
    {
        // 1. BCS Landing Page (If no year is provided)
        if (!$year) {
            return view('bcs.landing');
        }

        // 2. Default Category to MCQ if not provided (Standard for BCS Preliminary)
        $category = $category ?: 'MCQ';

        // 3. Main Query for BCS Questions
        // Assuming institution_id 1 is for BCS
        $posts = Post::where('institution_id', 1)
            ->where('year', $year)
            ->where('category', $category)
            ->with(['subject', 'institution'])
            ->orderBy('id', 'asc')
            ->get();

        // 4. Safety Fallback: If no posts found for that year/category
        if ($posts->isEmpty()) {
            return redirect()->route('bcs.index')->with('error', 'No questions found for this year.');
        }

        // 5. Navigation Logic (Next random year/set for continuous practice)
        $nextSet = Post::where('institution_id', 1)
            ->where('year', '!=', $year)
            ->inRandomOrder()
            ->first();

        // 6. Return the question view
        return view('questions.bcs', [
            'posts' => $posts,
            'year' => $year,
            'category' => $category,
            'nextSet' => $nextSet
        ]);
    }
}