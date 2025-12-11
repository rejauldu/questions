{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc>{{ url('/') }}</loc>
        <priority>1.0</priority>
    </url>

    @foreach ($posts as $post)
        @php
            $metaText = question_meta_text($post);
            $slug = url_slug($post->article, $metaText);
        @endphp

        <url>
            <loc>{{ route('questions.show', ['question' => $post->id, 'slug' => $slug]) }}</loc>
            <lastmod>{{ $post->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.80</priority>
        </url>
    @endforeach

</urlset>