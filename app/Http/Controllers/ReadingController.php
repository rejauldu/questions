<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\ViewedPost;
use App\Models\Institution;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReadingController extends Controller
{
    /**
     * Centralized constant for academic question categories.
     */
    private const QUESTION_TYPES = ['CQ', 'MCQ', 'Writing'];

    public function show($institution_slug, $subject_slug, $id, Request $request)
    {
        $q = Post::whereIn('category', self::QUESTION_TYPES)
                 ->with(['institution', 'subject', 'board'])
                 ->findOrFail($id);
                 
        $uuid = $request->cookie('examdao_uuid');
        $user = auth()->user();

        // 1. Mark as viewed immediately
        $this->syncViewedStatus($q->id, $uuid, $user);

        // 2. Find the next question using the updated priority ladder
        $next = $this->findNextQuestionRecord($q, $uuid, $user);

        // 3. Navigation URLs
        $prev = $this->getPreviousQuestion($q);
        $nextUrl = $this->generatePostUrl($next, $institution_slug, $subject_slug);
        $prevUrl = $this->generatePostUrl($prev, $institution_slug, $subject_slug);

        $isRead = $this->checkIfRead($q->id, $uuid, $user);

        return view('questions.reading-mode', [
            'q' => $q,
            'nextUrl' => $nextUrl,
            'prevUrl' => $prevUrl,
            'isRead' => $isRead,
            'title' => "Solution: " . Str::limit(strip_tags($q->article), 60),
        ]);
    }

    public function getNextQuestion($currentId, Request $request)
    {
        $q = Post::whereIn('category', self::QUESTION_TYPES)->findOrFail($currentId);
        $uuid = $request->cookie('examdao_uuid');
        $user = auth()->user();

        $next = $this->findNextQuestionRecord($q, $uuid, $user);

        if (!$next) return response()->json(['url' => url('/')]);

        return response()->json([
            'url' => $this->generatePostUrl($next)
        ]);
    }

    /**
     * UPDATED LOGIC: The Priority Ladder
     * 1. Same Topic (Unread)
     * 2. Same Chapter (Unread)
     * 3. Same Institution (Unread)
     * 4. Fallback: Same Category (Read/Viewed)
     */
    private function findNextQuestionRecord($q, $uuid, $user)
    {
        // Category must always stay the same as per requirements
        $baseScope = Post::whereIn('category', self::QUESTION_TYPES)
                        ->where('category', $q->category);

        $viewedIds = ViewedPost::where(function($query) use ($uuid, $user) {
            if ($user) $query->where('user_id', $user->id);
            else $query->where('visitor_uuid', $uuid);
        })->pluck('post_id')->toArray();

        $next = null;

        // --- STEP 1: Same Topic (Forward) ---
        if ($q->topic_name) {
            $next = (clone $baseScope)->where('institution_id', $q->institution_id)
                ->where('subject_id', $q->subject_id)
                ->where('chapter', $q->chapter)
                ->where('topic_name', $q->topic_name)
                ->where('id', '>', $q->id)
                ->whereNotIn('id', $viewedIds)
                ->orderBy('id', 'asc')
                ->first();
        }

        // --- STEP 2: Same Chapter (Forward) ---
        if (!$next && $q->chapter) {
            $next = (clone $baseScope)->where('institution_id', $q->institution_id)
                ->where('subject_id', $q->subject_id)
                ->where('chapter', $q->chapter)
                ->where('id', '>', $q->id)
                ->whereNotIn('id', $viewedIds)
                ->orderBy('id', 'asc')
                ->first();
        }

        // --- STEP 3: Same Subject (Forward) ---
        if (!$next) {
            $next = (clone $baseScope)->where('institution_id', $q->institution_id)
                ->where('subject_id', $q->subject_id)
                ->where('id', '>', $q->id)
                ->whereNotIn('id', $viewedIds)
                ->orderBy('id', 'asc')
                ->first();
        }

        // --- STEP 4: Reset - Absolute first unread in Subject ---
        if (!$next) {
            $next = (clone $baseScope)->where('institution_id', $q->institution_id)
                ->where('subject_id', $q->subject_id)
                ->whereNotIn('id', $viewedIds)
                ->orderBy('id', 'asc')
                ->first();
        }

        // --- STEP 5: Already Viewed (Oldest first) ---
        if (!$next) {
            $next = (clone $baseScope)->join('viewed_posts', 'posts.id', '=', 'viewed_posts.post_id')
                ->where('posts.institution_id', $q->institution_id)
                ->where('posts.subject_id', $q->subject_id)
                ->where(function($query) use ($uuid, $user) {
                    if ($user) $query->where('viewed_posts.user_id', $user->id);
                    else $query->where('viewed_posts.visitor_uuid', $uuid);
                })
                ->where('posts.id', '!=', $q->id)
                ->orderBy('viewed_posts.viewed_at', 'asc')
                ->select('posts.*')
                ->first();
        }

        // Final Fallback: Just return any relative post or the same one
        return $next ?? (clone $baseScope)->where('id', '!=', $q->id)->first() ?? $q;
    }

    private function syncViewedStatus($postId, $uuid, $user)
    {
        if (!$uuid && !$user) return;

        $searchCriteria = $user 
            ? ['user_id' => $user->id, 'post_id' => $postId]
            : ['visitor_uuid' => $uuid, 'post_id' => $postId];

        ViewedPost::updateOrCreate(
            $searchCriteria,
            ['viewed_at' => now()]
        );
    }

    private function checkIfRead($postId, $uuid, $user)
    {
        return ViewedPost::where('post_id', $postId)
            ->where(function($q) use ($uuid, $user) {
                if ($user) $q->where('user_id', $user->id);
                else $q->where('visitor_uuid', $uuid);
            })->exists();
    }

    private function getPreviousQuestion($q)
    {
        $baseScope = Post::whereIn('category', self::QUESTION_TYPES)
                         ->where('institution_id', $q->institution_id)
                         ->where('category', $q->category);

        return (clone $baseScope)->where('id', '<', $q->id)->orderBy('id', 'desc')->first() 
               ?? (clone $baseScope)->orderBy('id', 'desc')->first();
    }

    private function generatePostUrl($post, $instSlug = null, $subSlug = null)
    {
        if (!$post) return url('/');

        return route('reading.mode', [
            $instSlug ?? Str::slug($post->institution->name),
            $subSlug ?? Str::slug($post->subject->name),
            $post->id,
            url_slug($post->article, question_meta_text($post))
        ]);
    }

    public function trackView(Request $request)
    {
        $uuid = $request->cookie('examdao_uuid');
        $user = auth()->user();
        $this->syncViewedStatus($request->post_id, $uuid, $user);
        return response()->json(['status' => 'tracked']);
    }

    public function exam(Request $request, $institutionSlug = null, $subjectSlug = null, $category = null)
    {
        // 1. Handle "Select Institution" page
        if (!$institutionSlug) {
            return view('questions.institution', [
                'institutions' => Institution::select('id', 'name', 'slug')->get(),
                'posts' => Post::whereIn('category', self::QUESTION_TYPES)->latest()->paginate(32),
            ]);
        }

        $institution = Institution::where('slug', $institutionSlug)->firstOrFail();

        // 2. Base Query for the current context
        $query = Post::where('institution_id', $institution->id)
            ->whereIn('category', self::QUESTION_TYPES);

        // 3. Subject Filtering
        $selectedSubject = null;
        if ($subjectSlug && $subjectSlug !== 'all') {
            $selectedSubject = Subject::where('slug', $subjectSlug)
                ->where('institution_id', $institution->id)
                ->firstOrFail();
            $query->where('subject_id', $selectedSubject->id);
        }

        // 4. Calculate available filters 
        // We clone the query to see what's available for the current subject
        $filterQuery = clone $query;
        $availableCategories = $filterQuery->distinct()->pluck('category');
        
        $filterQuery = clone $query;
        // Get unique chapters that actually exist for this subject
        $availableChapters = $filterQuery->whereNotNull('chapter')
                                        ->distinct()
                                        ->orderBy('chapter')
                                        ->pluck('chapter');

        // --- CHAPTER VALIDATION ---
        // If a chapter is requested but doesn't exist for this subject, 
        // we set it to null so the user doesn't see an empty page.
        $requestedChapter = $request->query('chapter');
        if ($requestedChapter && !$availableChapters->contains((int)$requestedChapter)) {
            // Remove chapter from the request so the main query doesn't try to filter by it
            $request->merge(['chapter' => null]);
        }
        // --------------------------

        // 5. Apply filters
        if ($category && $availableCategories->contains($category)) {
            $query->where('category', $category);
        }
        
        if ($request->has('chapter') && $request->chapter) {
            $query->where('chapter', $request->chapter);
        }

        // 6. Get all valid subjects for this institution
        $subjects = Subject::where('institution_id', $institution->id)->orderBy('name')->get();

        return view('questions.exam', [
            'institution'         => $institution,
            'posts'               => $query->with(['institution', 'subject', 'board'])->latest()->paginate(32)->withQueryString(),
            'subjects'            => $subjects,
            'selectedSub'         => $subjectSlug ?? 'all',
            'category'            => $category,
            'availableCategories' => $availableCategories,
            'availableChapters'   => $availableChapters,
            'displayName'         => $selectedSubject ? $selectedSubject->name : $institution->name,
        ]);
    }

    public function getHomeStats() {
        return [
            'hsc_mcq_count' => Post::where('category', 'MCQ')->where('institution_id', 4)->count(),
            'bcs_count'     => Post::where('institution_id', 2)->count(),
            'total_solved'  => ViewedPost::count(),
        ];
    }

    public function hsc($subject = null, $year = null, $category = null, $board_id = null)
    {
        if (!$subject) return view('hsc.index');

        $subjectData = Subject::where('institution_id', 2)
            ->where('slug', $subject)
            ->orWhere('name', 'like', $subject . '%')
            ->first();

        if (!$subjectData) return redirect()->route('hsc.index'); 

        $subject_id = $subjectData->id;

        if ($subject && !$year) {
            return view('hsc.subject', [
                'subject' => $subjectData,
                'total_questions' => Post::where('subject_id', $subject_id)->count()
            ]);
        }

        $category = $category ?: 'MCQ';

        if (!$board_id) {
            $board_id = Post::where('institution_id', 2)
                ->where('subject_id', $subject_id)
                ->where('year', $year)
                ->where('category', $category)
                ->inRandomOrder()
                ->value('board_id') ?? Post::where('institution_id', 2)->where('subject_id', $subject_id)->latest()->value('board_id');
        }

        $posts = Post::where('institution_id', 2)
            ->where('subject_id', $subject_id)
            ->where('year', $year)
            ->where('category', $category)
            ->where('board_id', $board_id)
            ->with(['subject', 'institution', 'board'])
            ->orderBy('id', 'asc')
            ->get();

        $nextSet = Post::where('institution_id', 2)
            ->where('subject_id', $subject_id)
            ->where(function($q) use ($year, $board_id) {
                $q->where('year', '!=', $year)->orWhere('board_id', '!=', $board_id);
            })->inRandomOrder()->first();

        return view('questions.hsc', compact('posts', 'year', 'category', 'nextSet', 'board_id', 'subjectData'));
    }

    public function bcs($year = null, $category = null)
    {
        if ($year === 'random') {
            $randomYear = Post::where('institution_id', 4)->inRandomOrder()->value('year');
            return $randomYear ? redirect()->route('bcs.show', ['year' => $randomYear]) : redirect()->back();
        }

        $selectedYear = null;
        $finalCategory = null;
        $subjectId = null;

        if ($year) {
            if (is_numeric($year)) {
                $selectedYear = $year;
                $finalCategory = $category; 
            } else {
                $finalCategory = $year; 
                $selectedYear = null;
            }
        }

        $query = Post::where('institution_id', 4)->with(['subject', 'institution', 'board']);

        if ($finalCategory && $finalCategory !== 'all') {
            $subject = Subject::where('slug', $finalCategory)->first();
            if ($subject) {
                $subjectId = $subject->id;
                $query->where('subject_id', $subjectId);
            }
        }

        if ($selectedYear) $query->where('year', $selectedYear);

        if (!$selectedYear && !$subjectId) {
            $selectedYear = Post::where('institution_id', 4)->max('year') ?? 46;
            $query->where('year', $selectedYear);
        }

        $query->orderByRaw('year * (1 + RAND()) DESC');
        $posts = $query->paginate(200);

        $nextSet = Post::where('institution_id', 4)
            ->when($selectedYear, fn($q) => $q->where('year', '<', $selectedYear)->orderBy('year', 'desc'))
            ->first();

        return view('questions.bcs', [
            'posts' => $posts,
            'year' => $selectedYear,
            'category' => $finalCategory,
            'nextSet' => $nextSet,
            'isSubjectView' => (bool)$subjectId 
        ]);
    }
}