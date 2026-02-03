<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\SitemapController;

class GenerateSitemaps extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap index and question sitemaps weekly, then ping Google.';

    public function handle()
    {
        $this->info('Generating sitemaps...');

        // Call the SitemapController method
        $controller = new SitemapController();
        $controller->generate(); // This will regenerate all static + question sitemaps

        $this->info('Sitemaps generated successfully.');

        // Ping Google
        $sitemapIndexUrl = url('sitemaps/sitemap_index.xml');
        $googlePingUrl = 'https://www.google.com/ping?sitemap=' . urlencode($sitemapIndexUrl);

        try {
            $response = Http::get($googlePingUrl);

            if ($response->successful()) {
                $this->info("Google notified successfully.");
            } else {
                $this->warn("Google ping returned status: " . $response->status());
            }
        } catch (\Exception $e) {
            $this->error("Failed to ping Google: " . $e->getMessage());
        }
    }
}