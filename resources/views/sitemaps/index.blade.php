@php $xmlHeader = '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
{!! $xmlHeader !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($sitemaps as $file)
    <sitemap>
        <loc>{{ url('sitemaps/' . $file) }}</loc>
    </sitemap>
    @endforeach
</sitemapindex>