<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Subject;
use App\Models\Post;

class CloudflareService
{
    /**
     * LOCAL TRACKING LOGIC
     * Using Laravel's cache to track if we have visited these URLs recently.
     */

    /**
     * Check if a specific URL has been warmed locally.
     */
    public function isWarmed($url): bool
    {
        return Cache::has($this->getCacheKey($url));
    }

    /**
     * Mark a URL as warmed in the local cache tracker.
     */
    public function markAsWarmed($url): void
    {
        // Store the warmed status for 24 hours
        Cache::put($this->getCacheKey($url), true, now()->addHours(24));
    }

    /**
     * Generate a unique cache key for a URL.
     */
    private function getCacheKey($url): string
    {
        return 'warmed_url_' . md5($url);
    }

    /**
     * DISCOVERY LOGIC
     * Generate the list of all URLs to be cached.
     */
    public function getWarmupUrls(): array
    {
        // We cache the URL list generation itself for 1 hour to save DB resources
        return Cache::remember('cloudflare_warmup_list', 3600, function () {
            $urls = [
                url('/'),
                url('/questions'),
                url('/about'),
                url('/contact'),
                url('/exam/hsc'),
                url('/exam/bcs'),
            ];

            // 1. Add Subject URLs
            // Using cursor() to handle large datasets without memory crashes
            $subjects = Subject::where('institution_id', 2)->cursor();
            foreach ($subjects as $subject) {
                $urls[] = route('exam.show', [
                    'institution' => 'hsc', 
                    'subject' => $subject->slug
                ]);
            }

            // 2. Add ALL Question URLs from Posts
            $posts = Post::where('institution_id', 2)->latest()->cursor();
            foreach ($posts as $post) {
                $urls[] = route('questions.show', [
                    'question' => $post->id,
                    'slug' => url_slug($post->article, $post->q_meta)
                ]);
            }

            return $urls;
        });
    }

    /**
     * PURGE LOGIC
     * Clears local trackers and tells Cloudflare to delete its edge cache.
     */
    public function purgeEverything(): bool
    {
        // 1. Clear Local URL List Cache
        Cache::forget('cloudflare_warmup_list');

        // 2. Note: To clear all 'isWarmed' flags, we rely on the list refresh.
        // If you use a Cache Tag (e.g., Cache::tags(['warmed'])), you could clear all flags here.
        // For standard file cache, resetting the list is the primary reset.

        // 3. Purge Cloudflare Edge via API
        $zoneId = env('CLOUDFLARE_ZONE_ID');
        $email  = env('CLOUDFLARE_EMAIL');
        $apiKey = env('CLOUDFLARE_API_KEY');

        if (!$zoneId || !$email || !$apiKey) {
            \Log::error("Cloudflare credentials missing in .env");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'X-Auth-Email' => $email,
                'X-Auth-Key'   => $apiKey,
                'Content-Type' => 'application/json',
            ])->post("https://api.cloudflare.com/client/v4/zones/{$zoneId}/purge_cache", [
                'purge_everything' => true,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::error("Cloudflare API Purge Failed: " . $e->getMessage());
            return false;
        }
    }
}