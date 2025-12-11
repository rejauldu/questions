<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml file and store in public folder';

    public function handle()
    {
        $posts = Post::latest()->get();

        $xml = view('sitemap', compact('posts'))->render();

        file_put_contents(public_path('sitemap.xml'), $xml);

        $this->info('✅ sitemap.xml generated successfully!');
    }
}
