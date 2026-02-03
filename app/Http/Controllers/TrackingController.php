<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\ActivityLog;
use App\Models\ViewedPost;
use App\Models\Campaign;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TrackingController extends Controller
{
    /**
     * Centralized constant for academic question categories.
     */
    private const QUESTION_TYPES = ['CQ', 'MCQ', 'Writing'];

    /**
     * Track user activity (AJAX)
     */
    public function logActivity(Request $request, $postId)
    {
        $uuid = $request->cookie('examdao_uuid') ?? (string) Str::uuid();
        $authUserId = auth()->check() ? auth()->id() : null;

        Visitor::updateOrCreate(
            ['visitor_uuid' => $uuid],
            ['user_id' => $authUserId, 'updated_at' => now()]
        );

        $post = Post::find($postId);
        if ($post) {
            ActivityLog::create([
                'visitor_uuid'   => $uuid,
                'institution_id' => $post->institution_id,
                'subject_id'     => $post->subject_id,
                'action_type'    => 'view'
            ]);
        }

        return response()->json(['status' => 'tracked'])
            ->withCookie(cookie()->forever('examdao_uuid', $uuid));
    }

    /**
     * Consolidated User Personalization Logic
     * Handles: Section Reordering (Intent), Resume Link, and Hero HTML
     */
    public function getUserIntent(Request $request)
    {
        $uuid = $request->cookie('examdao_uuid');
        $user = auth()->user();

        if (!$uuid && !$user) {
            return response()->json(['status' => 'no_data']);
        }

        // 1. Identify User Intent (Top Interest based on Activity Logs)
        $topInterest = ActivityLog::where('visitor_uuid', $uuid)
            ->select('institution_id', 'subject_id', DB::raw('count(*) as total'))
            ->groupBy('institution_id', 'subject_id')
            ->orderBy('total', 'desc')
            ->first();

        $intent = 'NONE';
        if ($topInterest) {
            $intent = ($topInterest->institution_id == 2) ? 'HSC' : 'BCS';
        }

        // 2. Identify Last Viewed Post (For Resume Section)
        $lastViewed = ViewedPost::where($user ? ['user_id' => $user->id] : ['visitor_uuid' => $uuid])
            ->with(['post.subject', 'post.institution'])
            ->latest('viewed_at')
            ->first();

        $lastPostData = null;
        if ($lastViewed && $lastViewed->post) {
            $lastPostData = [
                'subject_name' => $lastViewed->post->subject->name,
                'url' => route('reading.mode', [
                    Str::slug($lastViewed->post->institution->name),
                    Str::slug($lastViewed->post->subject->name),
                    $lastViewed->post_id,
                    'resume-session'
                ])
            ];
        }

        // 3. Generate Hero HTML (Cached for performance)
        $heroCacheKey = "hero_data_" . ($user ? "u{$user->id}" : "v{$uuid}");
        $heroData = Cache::remember($heroCacheKey, now()->addMinutes(15), function () use ($uuid, $user, $topInterest) {
            if (!$topInterest) return null;

            // Fetch Viewed IDs to suggest something new
            $viewedIds = ViewedPost::where($user ? ['user_id' => $user->id] : ['visitor_uuid' => $uuid])->pluck('post_id');

            // Find Unread Post (Academic)
            $post = Post::whereIn('category', self::QUESTION_TYPES)
                ->where('institution_id', $topInterest->institution_id)
                ->where('subject_id', $topInterest->subject_id)
                ->whereNotIn('id', $viewedIds)
                // কমপ্লেক্স ট্যাগ ফিল্টারিং (Article এবং CQ 'a' কলামের জন্য)
                ->where(function($q) {
                    $tags = ['%<svg%', '%<table%', '%<ul%', '%<li%', '%<div%'];
                    foreach ($tags as $tag) {
                        $q->where('article', 'NOT LIKE', $tag);
                        $q->where('a', 'NOT LIKE', $tag); // CQ প্রশ্নের স্টেম বা ক-এর জন্য
                    }
                })
                ->with(['subject', 'institution'])
                ->inRandomOrder()
                ->first();

            // Fallback to oldest viewed if everything is read
            if (!$post) {
                $oldestId = ViewedPost::where($user ? ['user_id' => $user->id] : ['visitor_uuid' => $uuid])
                    ->whereHas('post', function($q) use ($topInterest) {
                        $q->where('institution_id', $topInterest->institution_id)
                          ->where('subject_id', $topInterest->subject_id);
                    })
                    ->orderBy('viewed_at', 'asc')
                    ->value('post_id');
                
                $post = Post::with(['subject', 'institution'])->find($oldestId);
            }

            if (!$post) return null;

            // Prepare Hero View Data
            $formattedInstName = institution($post->institution->name);
            $campaign = Campaign::where('institution_id', $post->institution_id)->where('is_active', 1)->first();
            
            $rawTeaser = ($post->category == 'CQ') ? ($post->a ?? $post->article) : $post->article;
            $teaser = Str::limit(strip_tags($rawTeaser), 80);

            $url = route('reading.mode', [
                institution($post->institution->name), 
                $post->subject->name, 
                $post->id, 
                url_slug($post->article)
            ]);

            $tagline = $campaign->tagline ?? "✨ আপনার জন্য: {$post->subject->name}";
            $headlineTemplate = $campaign->headline ?? 'আপনার :name প্রস্তুতির জন্য এই প্রশ্নটি দেখুন:';
            $headline = str_replace(':name', "<span class='text-warning-400'>{$formattedInstName}</span>", $headlineTemplate);
            $btnText = $campaign->button_text ?? 'পড়া শুরু করুন →';

            return [
                'html' => "
                    <div class='animate-fade-in-up flex flex-col items-center text-center'>
                        <div class='inline-flex items-center gap-2 px-3 py-1 bg-warning-400 text-primary-900 rounded-full text-[10px] font-bold uppercase tracking-wider mb-4 shadow-lg'>
                            {$tagline}
                        </div>
                        <h1 class='text-2xl sm:text-4xl font-extrabold text-white leading-tight mb-4 max-w-3xl px-2'>
                            {$headline}
                        </h1>
                        <p class='text-gray-300 italic mb-6 text-sm sm:text-base max-w-xl line-clamp-2'>
                            \"{$teaser}\"
                        </p>
                        <a href='{$url}' class='mb-6 px-8 py-3 bg-white text-primary-800 rounded-xl font-bold text-sm hover:bg-warning-400 hover:scale-105 transition-all shadow-xl'>
                            {$btnText}
                        </a>
                    </div>"
            ];
        });

        // 4. Return Final Combined JSON
        return response()->json([
            'status'    => 'success',
            'type'      => 'dynamic_home',
            'intent'    => $intent,
            'last_post' => $lastPostData,
            'hero'      => $heroData // Contains 'html' key for the tracker.js handleHeroContent
        ]);
    }

    /**
     * Return suggested questions based on history (Static partial)
     */
    public function getSuggestions(Request $request)
    {
        $uuid = $request->cookie('examdao_uuid');
        $user = auth()->user();
        
        if (!$uuid && !$user) {
            return view('partials.suggestions', ['suggestions' => collect()]);
        }

        $topInterest = ActivityLog::where('visitor_uuid', $uuid)
            ->select('institution_id', DB::raw('count(*) as total'))
            ->groupBy('institution_id')
            ->orderBy('total', 'desc')
            ->first();

        $suggestions = collect();

        if ($topInterest) {
            $suggestions = Post::whereIn('category', self::QUESTION_TYPES)
                ->where('institution_id', $topInterest->institution_id)
                ->whereNotNull('article')
                ->leftJoin('viewed_posts', function($join) use ($uuid, $user) {
                    $join->on('posts.id', '=', 'viewed_posts.post_id')
                         ->where(function($q) use ($uuid, $user) {
                             if ($user) $q->where('viewed_posts.user_id', $user->id);
                             else $q->where('viewed_posts.visitor_uuid', $uuid);
                         });
                })
                ->orderByRaw('viewed_posts.viewed_at IS NULL DESC') 
                ->orderBy('viewed_posts.viewed_at', 'asc')
                ->select('posts.*')
                ->take(6)
                ->get();
        }

        return view('partials.suggestions', compact('suggestions'));
    }
}