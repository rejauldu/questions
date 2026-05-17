<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Subject;
use App\Models\Institution;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    protected $chunkSize = 1000;

    // Academic categories
    private const QUESTION_TYPES = ['CQ', 'MCQ', 'Writing'];

    public function generate()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $sitemapFolder = public_path('sitemaps');

        if (!File::exists($sitemapFolder)) {
            File::makeDirectory($sitemapFolder, 0755, true);
        }

        File::cleanDirectory($sitemapFolder);

        // ✅ 1. Questions (use cursor for memory efficiency)
        $posts = Post::whereIn('category', self::QUESTION_TYPES)
            ->select('id', 'article', 'institution_id', 'subject_id', 'year', 'updated_at', 'category')
            ->latest()
            ->get();

        // ✅ 2. Blogs (optional)
        $blogs = Post::where('category', 'blog')
            ->select('id', 'article', 'updated_at')
            ->latest()
            ->get();

        // ✅ 3. Subjects (NEW - important)
        $subjects = Subject::where('status', 1)
            ->select('id', 'slug', 'updated_at')
            ->get();

        // ✅ 4. Institutions
        $institutions = Institution::select('id', 'slug')
            ->whereIn('id', [2, 4])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ Static Sitemap (home, subject list, institution etc.)
        |--------------------------------------------------------------------------
        */
        $staticXml = view('sitemaps.static', [
            'institutions' => $institutions,
            'subjects' => $subjects
        ])->render();

        File::put($sitemapFolder . '/sitemap-static.xml', $staticXml);

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ Subject Pages Sitemap (NEW)
        |--------------------------------------------------------------------------
        */
        $subjectXml = view('sitemaps.subjects', [
            'subjects' => $subjects
        ])->render();

        File::put($sitemapFolder . '/sitemap-subjects.xml', $subjectXml);

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ Blog Sitemap
        |--------------------------------------------------------------------------
        */
        $blogXml = view('sitemaps.blogs', [
            'blogs' => $blogs
        ])->render();

        File::put($sitemapFolder . '/sitemap-blogs.xml', $blogXml);

        /*
        |--------------------------------------------------------------------------
        | 4️⃣ Question Sitemaps (Chunked)
        |--------------------------------------------------------------------------
        */
        $questionSitemaps = [];
        $chunks = $posts->chunk($this->chunkSize);

        foreach ($chunks as $i => $chunk) {
            $filename = "sitemap-questions-" . ($i + 1) . ".xml";
            $questionSitemaps[] = $filename;

            $xml = view('sitemaps.questions', [
                'posts' => $chunk
            ])->render();

            File::put($sitemapFolder . '/' . $filename, $xml);
        }

        /*
        |--------------------------------------------------------------------------
        | 5️⃣ Master Sitemap Index
        |--------------------------------------------------------------------------
        */
        $allSitemaps = array_merge([
            'sitemap-static.xml',
            'sitemap-subjects.xml',
            'sitemap-blogs.xml'
        ], $questionSitemaps);

        $indexXml = view('sitemaps.index', [
            'sitemaps' => $allSitemaps
        ])->render();

        $indexFile = $sitemapFolder . '/sitemap_index.xml';
        File::put($indexFile, $indexXml);

        /*
        |--------------------------------------------------------------------------
        | 6️⃣ Ping Google
        |--------------------------------------------------------------------------
        */
        $sitemapUrl = url('sitemaps/sitemap_index.xml');
        $googlePingUrl = 'https://www.google.com/ping?sitemap=' . urlencode($sitemapUrl);

        try {
            file_get_contents($googlePingUrl);
        } catch (\Exception $e) {
            \Log::error("Google Sitemap Ping Failed: " . $e->getMessage());
        }

        return Response::file($indexFile, ['Content-Type' => 'application/xml']);
    }
}