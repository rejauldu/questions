<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\CloudflareService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ToolController extends Controller
{
    /**
     * 1. Display the Maintenance Dashboard
     * Synchronizes the URL list with local cache status for the UI.
     */
    public function index(CloudflareService $cf) 
    {
        $rawUrls = $cf->getWarmupUrls() ?? [];

        // Map the URLs to include their local "cached" status for the UI logic
        $urls = collect($rawUrls)->map(function($url) use ($cf) {
            return [
                'url' => $url,
                'is_warmed' => $cf->isWarmed($url)
            ];
        });

        return view('admin.tools', [
            'urls' => $urls,
            'warmedCount' => $urls->where('is_warmed', true)->count(),
            'totalCount' => $urls->count()
        ]);
    }

    /**
     * 2. Clear All Cache
     * Resets server-side trackers and purges Cloudflare edge.
     */
    public function clearCloudflare(CloudflareService $cf)
    {
        $cf->purgeEverything();
        
        return redirect()->action([self::class, 'index'])
                         ->with('status', 'Cloudflare Edge Purged and Local Tracking Reset.');
    }

    /**
     * 3. Warm Single URL (Called via AJAX)
     * Hits the URL and marks as warmed locally on success.
     */
    public function warmSingleUrl(Request $request, CloudflareService $cf)
    {
        $url = $request->input('url');
        
        if (!$url) {
            return response()->json(['success' => false, 'error' => 'URL is required'], 400);
        }
    
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) CacheWarmer/1.0',
                'Accept'     => 'text/html,application/xhtml+xml',
            ])->timeout(40)->get($url);
            
            if ($response->successful()) {
                $cf->markAsWarmed($url);
                return response()->json([
                    'success' => true,
                    'status'  => $response->status(),
                    'size'    => strlen($response->body()),
                ]);
            }
    
            return response()->json([
                'success' => false,
                'status'  => $response->status(),
            ], $response->status());
    
        } catch (\Exception $e) {
            Log::error("Cache Warmer Failed for {$url}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'status' => 522, 
                'message' => 'Connection timed out'
            ], 522);
        }
    }

    /* --- SVG & FLOWCHART TOOLS --- */

    public function svg(Post $post) {
        $svg_row = Post::where('article', 'like', '%<svg%')
            ->where('id', '>', $post->id)
            ->orderBy('id', 'asc')
            ->first();
    
        $svg = $svg_row ? $svg_row->id : null;
    
        return view('tools.svg', compact('post', 'svg'));
    }

    public function updateSvg(Request $request, Post $post) {
        $request->validate(['article' => 'required|string']);
        $post->update(['article' => $request->article]);
        return response()->json(['success' => true]);
    }
    
    public function flowchart() {
        return view('tools.flowchart');
    }

    /* --- DATABASE FIXING TOOLS (AJAX FRIENDLY) --- */

    public function fixLatexWrapper()
    {
        set_time_limit(120);
        $posts = Post::where('category', 'MCQ')
            ->where(function($query) {
                $query->where('d', 'LIKE', '%/%')
                      ->orWhere('d', 'LIKE', '%^%')
                      ->orWhere('d', 'LIKE', '%\\%'); 
            })
            ->where('id', '>', 6590)
            ->limit(500) 
            ->get();

        $updatedCount = 0;
        foreach ($posts as $post) {
            $original = $post->d;
            $fixed = $this->applyLatexWrapper($original);
            if ($original !== $fixed) {
                $post->update(['d' => $fixed]);
                $updatedCount++;
            }
        }
        return "SUCCESS: Updated {$updatedCount} MCQ options with LaTeX wrappers.";
    }
    
    private function applyLatexWrapper($text)
    {
        if (empty($text)) return $text;
        $ignoredUnits = ['m/s', 'km/h', 'kg/m', 'ft/s', 'cm/s'];
        $placeholders = [];
        $text = preg_replace_callback('/\$+.*\$+/U', function($m) use (&$placeholders) {
            $id = "___PH" . count($placeholders) . "___";
            $placeholders[$id] = $m[0];
            return $id;
        }, $text);

        $mathRegex = '~(?<![a-zA-Z\$])(?!\b(?:' . implode('|', $ignoredUnits) . ')\b)([\w\(\)\-\+\.]+\s?[\/\^]\s?[\w\(\)\-\+\.]+)(?![a-zA-Z\$])~i';
        $text = preg_replace_callback($mathRegex, function($matches) use ($ignoredUnits) {
            $match = trim($matches[0]);
            if (in_array(strtolower($match), $ignoredUnits)) return $matches[0];
            return '$' . $match . '$';
        }, $text);

        $text = preg_replace('/(?<!\$)\\\\[a-zA-Z]+(\{.*\}|)?(?!\$)/U', '$0$', $text);
        return strtr($text, $placeholders);
    }

    public function fixArticleFormatting()
    {
        set_time_limit(120);
        $posts = Post::where('article', 'LIKE', '%i.%')
            ->where('article', 'LIKE', '%ii.%')
            ->limit(500)
            ->get();

        $updatedCount = 0;
        foreach ($posts as $post) {
            $original = $post->article;
            $pattern = '/(?<!<br>)\s*\b(iii\.|ii\.|i\.)/i';
            $fixed = preg_replace($pattern, '<br>$1', $original);
            $fixed = preg_replace('/(i)<br>(i)/i', '$1$2', $fixed);
            $fixed = preg_replace('/(i)<br>(i)/i', '$1$2', $fixed); 
            $fixed = ltrim($fixed, '<br>');

            if ($original !== $fixed) {
                $post->update(['article' => $fixed]);
                $updatedCount++;
            }
        }
        return "SUCCESS: Formatting complete. Fixed {$updatedCount} articles.";
    }

    public function removeBrTags()
    {
        set_time_limit(150);
        $posts = Post::where('article', 'REGEXP', '<br\s*/?>')->limit(500)->get();
        $updatedCount = 0;
        foreach ($posts as $post) {
            $original = $post->article;
            $pattern = '/<br\s*\/?>/i';
            $fixed = preg_replace($pattern, "\n", $original);
            if ($original !== $fixed) {
                $post->update(['article' => $fixed]);
                $updatedCount++;
            }
        }
        return "SUCCESS: Replaced <br> with newlines in {$updatedCount} posts.";
    }
    
    /**
     * Removes starting Bengali numbers (১. to ২৫. ) from articles.
     */
    public function removeQNo()
    {
        set_time_limit(150);
        
        // We fetch posts where the article starts with a Bengali digit
        // Note: SQL 'REGEXP' support for UTF-8 varies, so we filter more broadly in SQL 
        // and precisely in PHP.
        $posts = Post::where('article', 'REGEXP', '^[১-৯]')
            ->limit(500)
            ->get();
    
        $updatedCount = 0;
        
        // Pattern for Bengali numbers 1 to 25 followed by a dot and space
        // ১-৯ (1-9), ১[০-৯] (10-19), ২[০-৫] (20-25)
        $pattern = '/^([১-৯]|১[০-৯]|২[০-৫])\.\s+/u';
    
        foreach ($posts as $post) {
            $original = $post->article;
            $fixed = preg_replace($pattern, '', $original);
    
            if ($original !== $fixed) {
                $post->update(['article' => $fixed]);
                $updatedCount++;
            }
        }
    
        return "SUCCESS: Cleaned up {$updatedCount} articles by removing starting Bengali numerals.";
    }
}