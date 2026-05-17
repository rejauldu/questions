<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

@foreach($subjects as $subject)
    <url>
        <loc>{{ url('/subject/' . $subject->slug) }}</loc>
        <lastmod>{{ optional($subject->updated_at)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
@endforeach

</urlset>