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

    public function exam(Request $request, $institutionSlug = null, $subjectSlug = null, $category = null)
    {
        $query = Post::query()
            ->whereIn('category', self::QUESTION_TYPES)
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

        // --- CHAPTER FILTER LOGIC ---
        // Only apply chapter filter if it's provided in the query string (?chapter=1)
        if ($request->has('chapter') && $request->chapter !== null) {
            $query->where('chapter', $request->chapter);
        }
        // ----------------------------

        $subjects = Subject::where('institution_id', $institution->id)
            ->orderBy('name')
            ->get();

        $uniqueSubjectsList = $subjects->unique(function ($subject) {
            $pattern = '/\s+(1st|2nd|১ম|২য়)$/iu';
            return trim(preg_replace($pattern, '', $subject->name));
        })->values();

        $displayName = '';

        if ($subjectSlug && $subjectSlug !== 'all') {
            // We use where('slug', 'like', ...) to match subjects like 'physics-1st' when slug is just 'physics'
            $subject = Subject::where('slug', 'like', $subjectSlug.'%')
                ->where('institution_id', $institution->id)
                ->firstOrFail();

            $displayName = $subject->name;
            $query->where('subject_id', $subject->id);
        }

        if ($category) {
            $query->where('category', $category);
        }

        // .withQueryString() is crucial here to keep ?chapter=X when clicking pagination links
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
    public function hsc($subject = null, $year = null, $category = null, $board_id = null)
    {
        // 1. If no subject at all, return the main HSC landing page
        if (!$subject) {
            return view('hsc.index'); // Or your 'hsc.index'
        }

        // 2. Identify the Subject
        $subjectData = Subject::where('institution_id', 2)
            ->where('slug', $subject) // Using slug is better for URLs
            ->orWhere('name', 'like', $subject . '%')
            ->first();

        if (!$subjectData) {
            // Fallback if subject name doesn't match: return to main hsc index
            return redirect()->route('hsc.index'); 
        }

        $subject_id = $subjectData->id;

        // 3. NEW LOGIC: If Subject exists but NO Year is provided
        // This allows you to show the "HSC English Preparation" view
        if ($subject && !$year) {
            return view('hsc.subject', [
                'subject' => $subjectData,
                // You can pass stats here for your "Active Learning" feel
                'total_questions' => Post::where('subject_id', $subject_id)->count()
            ]);
        }

        // --- Rest of the code runs ONLY if Year is provided ---

        // Default Category if year exists
        $category = $category ?: 'MCQ';

        // 4. Determine which board to show
        if (!$board_id) {
            $board_id = Post::where('institution_id', 2)
                ->where('subject_id', $subject_id)
                ->where('year', $year)
                ->where('category', $category)
                ->inRandomOrder()
                ->value('board_id');

            // Fallback: Latest available board for this subject
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

        // 6. Logic for "Next Set"
        $nextSet = Post::where('institution_id', 2)
            ->where('subject_id', $subject_id)
            ->where(function($q) use ($year, $board_id) {
                $q->where('year', '!=', $year)
                ->orWhere('board_id', '!=', $board_id);
            })
            ->inRandomOrder()
            ->first();

        return view('questions.hsc', compact('posts', 'year', 'category', 'nextSet', 'board_id', 'subjectData'));
    }

    public function bcs($year = null, $category = null)
    {
        // 1. Enhanced "Random" Logic: Pick a random BCS year, not just a random post
        if ($year === 'random') {
            $randomYear = Post::where('institution_id', 4)
                ->inRandomOrder()
                ->value('year');

            return $randomYear 
                ? redirect()->route('bcs.show', ['year' => $randomYear])
                : redirect()->back();
        }

        $selectedYear = null;
        $finalCategory = null;
        $subjectId = null;

        // 2. Logic: Identify Year or Subject Slug
        if ($year) {
            if (is_numeric($year)) {
                $selectedYear = $year;
                $finalCategory = $category; 
            } else {
                $finalCategory = $year; 
                $selectedYear = null;
            }
        }

        // 3. Main Query
        $query = Post::where('institution_id', 4)
            ->with(['subject', 'institution', 'board']);

        if ($finalCategory && $finalCategory !== 'all') {
            $subject = Subject::where('slug', $finalCategory)->first();
            if ($subject) {
                $subjectId = $subject->id;
                $query->where('subject_id', $subjectId);
            }
        }

        if ($selectedYear) {
            $query->where('year', $selectedYear);
        }

        // 4. Default Behavior: Show latest BCS (Descending)
        if (!$selectedYear && !$subjectId) {
            $selectedYear = Post::where('institution_id', 4)->max('year') ?? 46;
            $query->where('year', $selectedYear);
        }

        // 5. Weighted Random Logic
        // We order by (year * random_factor) descending. 
        // This ensures that 46th BCS posts have a much higher statistical 
        // probability of appearing first than 10th BCS posts.
        
        $query->orderByRaw('year * (1 + RAND()) DESC');

        $posts = $query->paginate(200);

        // 6. Navigation Logic
        // For the 'Next' button, we still want a logical flow (Descending Year)
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