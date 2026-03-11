<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HscController extends Controller
{
    /**
     * HSC Landing Page (Index)
     * URL: /hsc
     */
    public function index()
    {
        return view('hsc.index');
    }

    /**
     * Handle HSC Subject Landing and Question Views
     */
    public function hsc($subject = null, $year = null, $category = null, $board_id = null)
    {
        // 1. Safety Check
        if (!$subject) {
            return redirect()->route('hsc.index');
        }

        // 2. Identify the Subject
        $subjectData = Subject::where('institution_id', 2)
            ->where(function($q) use ($subject) {
                $q->where('slug', $subject)
                  ->orWhere('name', 'like', $subject . '%');
            })
            ->first();

        if (!$subjectData) {
            return redirect()->route('hsc.index'); 
        }

        $subject_id = $subjectData->id;

        // 3. Subject Landing Page (English / ICT)
        if (!$year) {
            // Fixed the syntax in the match expression
            $viewName = match($subjectData->slug) {
                'english-1st', 'english-2nd', 'english' => 'english',
                'ict' => 'ict',
                default => 'ict' 
            };
            
            // Passing subjectData so you can use the title/slug in the view
            return view("hsc.$viewName", compact('subjectData'));
        }

        // ---------------------------------------------------------
        // The following logic runs ONLY if a Year is provided
        // ---------------------------------------------------------

        $category = $category ?: 'MCQ';

        // 4. Determine Board
        if (!$board_id) {
            $board_id = Post::where('institution_id', 2)
                ->where('subject_id', $subject_id)
                ->where('year', $year)
                ->where('category', $category)
                ->inRandomOrder()
                ->value('board_id');

            if (!$board_id) {
                $board_id = Post::where('institution_id', 2)
                    ->where('subject_id', $subject_id)
                    ->latest()
                    ->value('board_id');
            }
        }

        // 5. Main Query
        $posts = Post::where('institution_id', 2)
            ->where('subject_id', $subject_id)
            ->where('year', $year)
            ->where('category', $category)
            ->where('board_id', $board_id)
            ->with(['subject', 'institution', 'board'])
            ->orderBy('id', 'asc')
            ->get();

        // 6. Navigation Logic
        $nextSet = Post::where('institution_id', 2)
            ->where('subject_id', $subject_id)
            ->where(function($q) use ($year, $board_id) {
                $q->where('year', '!=', $year)
                  ->orWhere('board_id', '!=', $board_id);
            })
            ->inRandomOrder()
            ->first();

        return view('questions.hsc', [
            'posts' => $posts,
            'year' => $year,
            'category' => $category,
            'nextSet' => $nextSet,
            'board_id' => $board_id,
            'subjectData' => $subjectData
        ]);
    }
}