<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Post;
use App\Models\Board;
use Illuminate\Http\Request;
use App\Services\SeoService;

class SubjectController extends Controller
{
    public function show(Request $request, $subject_slug, SeoService $seoService)
    {
        // 1. Fetch the subject and its institution details
        $subject = Subject::where('slug', $subject_slug)
            ->with('institution')
            ->firstOrFail();

        // 2. Get unique Board/Year combinations for the filter menu
        $availableFilters = Post::where('subject_id', $subject->id)
            ->select('board_id', 'year')
            ->distinct()
            ->with('board')
            ->orderBy('year', 'desc')
            ->get();

        // 3. Build the main base query
        $query = Post::where('subject_id', $subject->id)
            ->where('institution_id', $subject->institution_id)
            ->with(['board', 'user']);

        // 4. Default State: If no filters are in the URL, pick the latest one
        if (!$request->filled('year') && $availableFilters->isNotEmpty()) {
            $latest = $availableFilters->first();
            $request->merge([
                'board' => $latest->board_id,
                'year' => $latest->year
            ]);
        }

        // 5. Dynamic Toggle Logic
        $showToggle = false;
        $availableCategories = collect();

        if ($request->filled('board') && $request->filled('year')) {
            $query->where('board_id', $request->board)
                  ->where('year', $request->year);

            // Define categories that trigger the toggle
            // You can easily add more strings to this array later
            $toggleCategories = ['MCQ', 'CQ', 'Writing', 'Image'];

            // Fetch which categories actually exist for this specific selection
            $availableCategories = Post::where('subject_id', $subject->id)
                ->where('board_id', $request->board)
                ->where('year', $request->year)
                ->whereIn('category', $toggleCategories)
                ->distinct()
                ->pluck('category');

            // Activate toggle if more than one category exists (e.g., MCQ + Writing)
            $showToggle = $availableCategories->count() > 1;

            // Apply Category Filter
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            } else {
                // Default to the first available category found in the database for this year/board
                $default = $availableCategories->first() ?? 'MCQ';
                $query->where('category', $default);
                $request->merge(['category' => $default]);
            }
        } elseif ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // 6. Finalize with Pagination and persist URL parameters
        $posts = $query->latest()->paginate(35)->withQueryString();
        
        // Check if the collection has any posts
        $firstPost = $posts->first(); 

        if ($firstPost) {
            $seo = $seoService->generate($firstPost);
        } else {
            // Fallback SEO if no posts are found
            $seo = [
                'title' => "{$subject->institution->name} {$subject->bangla} Questions",
                'description' => "Practice {$subject->bangla} questions and solutions on ExamDao.",
            ];
        }

        // 7. Data for Sidebar
        $allSubjects = Subject::where('institution_id', $subject->institution_id)->get();

        return view('subjects.show', compact(
            'subject', 
            'posts', 
            'availableFilters', 
            'allSubjects', 
            'showToggle',
            'availableCategories' // Passed so you can loop through tabs in Blade
        ));
    }
}