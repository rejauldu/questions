@php $xmlHeader = '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
{!! $xmlHeader !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($posts as $post)
        @php
            $q_meta = question_meta_text($post);
            $slug = url_slug($post->article, $q_meta);
            $fullUrl = route('questions.show', ['question' => $post->id, 'slug' => $slug]);
        @endphp
        <url>
            <loc>{{ urldecode($fullUrl) }}</loc>
            <priority>0.9</priority>
            <lastmod>{{ $post->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        </url>
    @endforeach
</urlset>