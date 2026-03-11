@php
    echo '<?xml version="1.0" encoding="UTF-8"?>';
@endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    @foreach ($blogs as $blog)
        @php
            // If you have a named route:
            // $url = route('blogs.show', $blog->slug);

            // Or direct URL (safer for sitemap)
            $url = url('/blogs/' . $blog->slug);
        @endphp

        <url>
            <loc>{{ $url }}</loc>

            @if($blog->updated_at)
                <lastmod>{{ $blog->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            @endif

            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

</urlset>