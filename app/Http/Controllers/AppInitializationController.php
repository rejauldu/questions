<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Visitor, ActivityLog, ViewedPost, Post};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{DB, Cache, Redis};

class AppInitializationController extends Controller
{
    private const QUESTION_TYPES = ['CQ', 'MCQ', 'Writing'];

    public function init(Request $request)
    {
        $user = auth()->user();
        $uuid = $request->cookie('examdao_uuid') ?? (string) Str::uuid();
        $postId = $request->input('post_id'); // Passed from JS if on a post page

        // --- 1. Auth Status Logic ---
        $authData = ['auth' => false, 'user' => null];
        if ($user) {
            $authData['auth'] = true;
            $authData['user'] = Cache::remember("user_stat_{$user->id}", 3600, fn() => [
                'name'    => $user->name,
                'initial' => $user->initial,
                'role'    => $user->role,
            ]);
        }

        // --- 2. Tracking Logic (Only if postId exists) ---
        if ($postId) {
            $this->trackActivity($uuid, $user?->id, $postId);
        }

        // --- 3. Personalization Logic (Intent, Last Post, Hero) ---
        $topInterest = $this->getTopInterest($uuid);
        $intent = ($topInterest && $topInterest->institution_id == 2) ? 'HSC' : 'BCS';

        $lastPostData = $this->getLastPost($uuid, $user?->id);
        
        $heroData = $this->getHeroData($uuid, $user?->id, $topInterest);

        return response()->json([
            'auth'      => $authData['auth'],
            'user'      => $authData['user'],
            'csrf'      => csrf_token(),
            'intent'    => $intent,
            'last_post' => $lastPostData,
            'hero'      => $heroData,
            'status'    => 'success'
        ])->withCookie(cookie()->forever('examdao_uuid', $uuid));
    }

    private function trackActivity($uuid, $userId, $postId)
    {
        Cache::remember("visitor_seen_{$uuid}", 300, function() use ($uuid, $userId) {
            Visitor::updateOrCreate(['visitor_uuid' => $uuid], ['user_id' => $userId]);
            return true;
        });

        $post = Post::find($postId);
        if ($post) {
            $intentKey = "intent_count:{$uuid}:{$post->institution_id}:{$post->subject_id}";
            Redis::incr($intentKey);
            Redis::expire($intentKey, 604800);

            ActivityLog::create([
                'visitor_uuid'   => $uuid,
                'institution_id' => $post->institution_id,
                'subject_id'     => $post->subject_id,
                'action_type'    => 'view'
            ]);
            
            Redis::sadd("viewed_set:{$uuid}", $postId);
            Redis::expire("viewed_set:{$uuid}", 604800);
        }
    }

    private function getLastPost($uuid, $userId)
    {
        return Cache::remember("last_viewed:{$uuid}", 300, function() use ($userId, $uuid) {
            $viewed = ViewedPost::where($userId ? ['user_id' => $userId] : ['visitor_uuid' => $uuid])
                ->with(['post.subject', 'post.institution'])
                ->latest('viewed_at')
                ->first();

            if (!$viewed || !$viewed->post) return null;

            return [
                'subject_name' => $viewed->post->subject->bangla,
                'url' => route('reading.mode', [
                    Str::slug($viewed->post->institution->name),
                    Str::slug($viewed->post->subject->name),
                    $viewed->post_id,
                    'resume-session'
                ])
            ];
        });
    }

    private function getHeroData($uuid, $userId, $topInterest)
    {
        $heroCacheKey = "hero_data_" . ($userId ? "u{$userId}" : "v{$uuid}");
        return Cache::remember($heroCacheKey, 900, function () use ($uuid, $topInterest) {
            if (!$topInterest) return null;

            $viewedIds = Redis::smembers("viewed_set:{$uuid}");
            $post = Post::whereIn('category', self::QUESTION_TYPES)
                ->where('institution_id', $topInterest->institution_id)
                ->where('subject_id', $topInterest->subject_id)
                ->where('has_complex_html', 0)
                ->whereNotIn('id', $viewedIds)
                ->with(['subject', 'institution'])
                ->inRandomOrder()->first();

            return $post ? ['html' => $this->formatHeroHtml($post)] : null;
        });
    }

    private function getTopInterest($uuid)
    {
        return Cache::remember("top_interest:{$uuid}", 3600, function() use ($uuid) {
            return ActivityLog::where('visitor_uuid', $uuid)
                ->select('institution_id', 'subject_id', DB::raw('count(*) as total'))
                ->groupBy('institution_id', 'subject_id')
                ->orderBy('total', 'desc')->first();
        });
    }

    private function formatHeroHtml($post) {
        return "<div class='hero-box'>...</div>"; // Your formatting logic
    }
}