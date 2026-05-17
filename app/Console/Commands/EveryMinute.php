<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\TransformerController;
use App\Http\Controllers\SitemapController; // ✅ ADD THIS
use App\Http\Controllers\AiController;
use App\Services\GeminiService;

class EveryMinute extends Command
{
    protected $signature = 'cronjob';
    protected $description = 'Triggers AI + Sitemap generation';

    public function handle(GeminiService $service)
    {
        try {

            // ✅ 1. Gemini কাজ
            // $service->fillMissingTopics();

            // 2. Sitemap
            // $sitemap = app(SitemapController::class);
            // $sitemap->generate();
            
            //3. OCR
            $ai = app(AiController::class);
            // $ai->processOcrQueue();
            $ai->mcq();

        } catch (\Exception $e) {
            Log::error('AI_CRON_DEBUG: Cron failed! ' . $e->getMessage());
        }
    }
}