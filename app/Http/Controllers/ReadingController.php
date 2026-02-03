<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\ViewedPost;
use App\Models\Institution;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class ReadingController extends Controller
{
    /**
     * Centralized constant for academic question categories.
     * This prevents Blog or Static pages from leaking into the study flow.
     */
    private const QUESTION_TYPES = ['CQ', 'MCQ', 'Writing'];

    public function show($institution_slug, $subject_slug, $id, Request $request)
    {
        // 1. Fetch only if it belongs to question categories
        $q = Post::whereIn('category', self::QUESTION_TYPES)
                 ->with(['institution', 'subject', 'board'])
                 ->findOrFail($id);
                 
        $uuid = $request->cookie('examdao_uuid');
        $user = auth()->user();

        // 2. Mark as viewed immediately upon landing
        $this->syncViewedStatus($q->id, $uuid, $user);

        // 3. Find the next question using the priority ladder
        $next = $this->findNextQuestionRecord($q, $uuid, $user);

        // 4. Navigation URLs
        $prev = $this->getPreviousQuestion($q);
        $nextUrl = $this->generatePostUrl($next, $institution_slug, $subject_slug);
        $prevUrl = $this->generatePostUrl($prev, $institution_slug, $subject_slug);

        // 5. Status Badge logic
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
     * SHARED LOGIC: The Priority Ladder
     * Now strictly scoped to academic categories.
     */
    private function findNextQuestionRecord($q, $uuid, $user)
    {
        $baseScope = Post::whereIn('category', self::QUESTION_TYPES)
                        ->where('institution_id', $q->institution_id)
                        ->where('subject_id', $q->subject_id)
                        ->where('category', $q->category);

        $viewedIds = ViewedPost::where(function($query) use ($uuid, $user) {
            if ($user) $query->where('user_id', $user->id);
            else $query->where('visitor_uuid', $uuid);
        })->pluck('post_id')->toArray();

        $next = null;

        // 1. Same Chapter + Same Topic + Unread
        if ($q->chapter && $q->topic_name) {
            $next = (clone $baseScope)->where('chapter', $q->chapter)->where('topic_name', $q->topic_name)
                ->where('id', '>', $q->id)->whereNotIn('id', $viewedIds)->orderBy('id', 'asc')->first();
        }

        // 2. Same Chapter + Unread
        if (!$next && $q->chapter) {
            $next = (clone $baseScope)->where('chapter', $q->chapter)
                ->where('id', '>', $q->id)->whereNotIn('id', $viewedIds)->orderBy('id', 'asc')->first();
        }

        // 3. Any Chapter + Unread (Forward)
        if (!$next) {
            $next = (clone $baseScope)->where('id', '>', $q->id)->whereNotIn('id', $viewedIds)
                ->orderBy('id', 'asc')->first();
        }

        // 4. Any Chapter + Unread (Absolute first unread)
        if (!$next) {
            $next = (clone $baseScope)->whereNotIn('id', $viewedIds)->orderBy('id', 'asc')->first();
        }

        // 5. Viewed (Oldest first)
        if (!$next) {
            $next = (clone $baseScope)->join('viewed_posts', 'posts.id', '=', 'viewed_posts.post_id')
                ->where(function($query) use ($uuid, $user) {
                    if ($user) $query->where('viewed_posts.user_id', $user->id);
                    else $query->where('viewed_posts.visitor_uuid', $uuid);
                })
                ->where('posts.id', '!=', $q->id)
                ->orderBy('viewed_posts.viewed_at', 'asc')
                ->select('posts.*')
                ->first();
        }

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
                         ->where('subject_id', $q->subject_id)
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

    public function exam($institutionSlug = null, $subjectSlug = null, $category = null)
    {
        $query = Post::query()
            ->whereIn('category', self::QUESTION_TYPES) // Only show academic questions
            ->with(['institution', 'subject', 'board'])
            ->orderByDesc('year')
            ->orderByDesc('created_at');
    
        if (!$institutionSlug) {
            return view('questions.institution', [
                'institutions' => Institution::select('id', 'name', 'slug')->get(),
                'posts' => $query->paginate(32),
            ]);
        }
    
        $institution = Institution::where('slug', $institutionSlug)->firstOrFail();
        $query->where('institution_id', $institution->id);
    
        $subjects = Subject::where('institution_id', $institution->id)
            ->orderBy('name')
            ->get();

        $uniqueSubjectsList = $subjects->unique(function ($subject) {
            return trim(str_replace(['1st', '2nd', '১ম', '২য়'], '', strtolower($subject->name)));
        })->values(); 
    
        $displayName = '';
    
        if ($subjectSlug && $subjectSlug !== 'all') {
            $subject = Subject::where('slug', $subjectSlug)
                ->where('institution_id', $institution->id)
                ->firstOrFail();
    
            $displayName = $subject->name;
            $query->where('subject_id', $subject->id);
        }
    
        if ($category) {
            $query->where('category', $category);
        }
    
        $posts = $query->paginate(32)->withQueryString();
    
        return view('questions.portal', [
            'institution'   => $institution,
            'posts'         => $posts,
            'subjects'      => $uniqueSubjectsList,
            'selectedSub'   => $subjectSlug,
            'category'      => $category,
            'displayName'   => $displayName ?: $institution->name,
        ]);
    }
    public function getHomeStats() {
        return [
            'hsc_mcq_count' => Post::where('category', 'MCQ')->where('institution_id', 4)->count(),
            'bcs_count'     => Post::where('institution_id', 2)->count(),
            'total_solved'  => ViewedPost::count(),
        ];
    }
    /**
     * Handle HSC specific year/category requests
     * institution_id = 2
     */
    public function hsc($subject, $year = null, $category = null, $board_id = null)
    {
        // 1. Set Subject Defaults
        $subject_id = null; // Initialize

        if ($subject) {
            $subject_id = Subject::where('institution_id', 2)
                ->where('name', 'like', $subject . '%')
                ->value('id');
        }

        // If no subject match found, pick a random one
        if (!$subject_id) {
            $subject_id = Subject::where('institution_id', 2)
                ->inRandomOrder()
                ->value('id');
        }

        // Default Year and Category
        $year = $year ?: (date('Y') - 1);
        $category = $category ?: Arr::random(['MCQ', 'CQ']);

        // 2. Determine which board to show
        if (!$board_id) {
            $board_id = Post::where('institution_id', 2)
                ->where('subject_id', $subject_id) // Match the subject
                ->where('year', $year)
                ->where('category', $category)
                ->inRandomOrder()
                ->value('board_id');

            // Fallback: If no questions exist for that specific year/subject/board combo, 
            // find the latest available board for this subject
            if (!$board_id) {
                $board_id = Post::where('institution_id', 2)
                    ->where('subject_id', $subject_id)
                    ->latest()
                    ->value('board_id');
            }
        }

        // 3. Main Query - Added subject_id filter
        $posts = Post::where('institution_id', 2)
            ->where('subject_id', $subject_id) // <--- CRITICAL FIX
            ->where('year', $year)
            ->where('category', $category)
            ->where('board_id', $board_id)
            ->with(['subject', 'institution', 'board'])
            ->orderBy('id', 'asc')
            ->get();

        // 4. Logic for "Next Set" (Stick to the same subject, different board/year)
        $nextSet = Post::where('institution_id', 2)
            ->where('subject_id', $subject_id)
            ->where(function($q) use ($year, $board_id) {
                $q->where('year', '!=', $year)
                ->orWhere('board_id', '!=', $board_id);
            })
            ->inRandomOrder()
            ->first();

        return view('questions.hsc', compact('posts', 'year', 'category', 'nextSet', 'board_id'));
    }

    public function bcs($year = null, $category = null)
    {
        // 1. Handle "Random" request
        if ($year === 'random') {
            $randomPost = Post::where('institution_id', 4)->inRandomOrder()->first();
            if ($randomPost) {
                return redirect()->route('bcs.show', ['year' => $randomPost->year]);
            }
        }

        // 2. Set Default Year (Latest BCS)
        if (!$year || !is_numeric($year)) {
            // Cache this or use a simple query to find the max year
            $year = Post::where('institution_id', 4)->max('year') ?? 46;
        }

        // 3. Main Query
        $query = Post::where('institution_id', 4)
            ->where('year', $year)
            ->with(['subject', 'institution', 'board']);

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        // Get all 200 questions for the "Paper Texture" full view
        $posts = $query->orderBy('id', 'asc')->get();

        // 4. Find the Previous BCS (for better study flow: 46 -> 45 -> 44)
        $nextSet = Post::where('institution_id', 4)
            ->where('year', '<', $year) 
            ->orderBy('year', 'desc')
            ->first();

        // Fallback: If at the very first BCS, pick a random year
        if (!$nextSet) {
            $nextSet = Post::where('institution_id', 4)
                ->where('year', '!=', $year)
                ->inRandomOrder()
                ->first();
        }

        return view('questions.bcs', compact('posts', 'year', 'category', 'nextSet'));
    }
}