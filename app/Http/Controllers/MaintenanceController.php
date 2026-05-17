<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Post;
use App\Models\Subject;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class MaintenanceController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Updates short_article and hash_a for rows where they are missing.
     */
    public function updateNormalizedData()
    {
        $processedCount = 0;
        $limit = 150;
    
        // Remove the ->where('has_complex_html', 0) to process all records needing normalization
        Post::where(function($q) {
                $q->whereNull('short_article')
                  ->orWhereNull('hash_a');
            })
            ->chunk(500, function ($posts) use (&$processedCount, $limit) {
                foreach ($posts as $post) {
                    $updated = false;
    
                    // 1. Process Article (Only if short_article is currently null)
                    if (is_null($post->short_article)) {
                        $cleanArticle = strtolower(strip_tags($post->article ?? ''));
                        $normalizedArticle = preg_replace('/\s+/', '', $cleanArticle);
                        
                        // Only save if there is actually text remaining after stripping tags
                        if (!empty($normalizedArticle)) {
                            $post->short_article = mb_substr($normalizedArticle, 0, 150);
                            $updated = true;
                        } else {
                            // Optional: Set to empty string to avoid processing again if it's just empty/HTML-only
                            $post->short_article = '';
                            $updated = true;
                        }
                    }
    
                    // 2. Process hash_a (Always process if null)
                    if (is_null($post->hash_a)) {
                        $cleanA = strtolower(strip_tags($post->a ?? ''));
                        $normalizedA = preg_replace('/[^a-z0-9\x{0980}-\x{09FF}]/u', '', $cleanA);
                        
                        // Always store the normalized 'a' field for searchability
                        $post->hash_a = mb_substr($normalizedA, 0, $limit);
                        $updated = true;
                    }
    
                    if ($updated) {
                        $post->save();
                        $processedCount++;
                    }
                }
            });
    
        return "Successfully updated {$processedCount} records.";
    }
    
    public function fixTable()
    {
        $updatedCount = 0;
    
        // Fetch posts that have a table tag in either field
        // We search for <table to be safe
        Post::where(function ($q) {
            $q->where('article', 'LIKE', '%<table%')
              ->orWhere('explanation', 'LIKE', '%<table%');
        })
        ->chunk(200, function ($posts) use (&$updatedCount) {
            foreach ($posts as $post) {
                $changed = false;
    
                // Pattern: Look for <table and ensure it doesn't already contain class="table"
                // We use a regex that matches <table followed by anything that isn't class="table"
                $pattern = '/<table(?![^>]*class=["\']?table["\']?)/i';
                $replacement = '<table class="table"';
    
                // Check and update article
                if ($post->article && preg_match($pattern, $post->article)) {
                    $post->article = preg_replace($pattern, $replacement, $post->article);
                    $changed = true;
                }
    
                // Check and update explanation
                if ($post->explanation && preg_match($pattern, $post->explanation)) {
                    $post->explanation = preg_replace($pattern, $replacement, $post->explanation);
                    $changed = true;
                }
    
                if ($changed) {
                    $post->save();
                    $updatedCount++;
                }
            }
        });
    
        return "Successfully updated {$updatedCount} records.";
    }
}