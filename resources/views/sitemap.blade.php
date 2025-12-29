@php $xmlHeader = '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
{!! $xmlHeader !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- 1. Home --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <priority>1.0</priority>
        <changefreq>daily</changefreq>
    </url>

    {{-- 2. Static Pages (Trust Signals) --}}
    <url>
        <loc>{{ url('/about') }}</loc>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ url('/contact') }}</loc>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ url('/chatbot') }}</loc>
        <priority>0.6</priority>
    </url>

    {{-- 3. Hierarchical Routes --}}
    @foreach ($institutions as $institution)
        {{-- Level 1: Institution --}}
        <url>
            <loc>{{ route('exam.show', $institution->slug) }}</loc>
            <priority>0.8</priority>
            <changefreq>weekly</changefreq>
        </url>

        @foreach ($subjects as $subject)
            {{-- Level 2: Institution + Subject --}}
            <url>
                <loc>{{ route('exam.show', [$institution->slug, $subject->slug]) }}</loc>
                <priority>0.7</priority>
                <changefreq>weekly</changefreq>
            </url>

            {{-- Level 3: Institution + Subject + Year (Hierarchy) --}}
            {{-- This dynamically finds years that actually have questions for this pair --}}
            @php
                $years = $posts->where('institution_id', $institution->id)
                               ->where('subject_id', $subject->id)
                               ->pluck('year')
                               ->unique();
            @endphp
            @foreach ($years as $year)
                <url>
                    <loc>{{ route('exam.show', [$institution->slug, $subject->slug, $year]) }}</loc>
                    <priority>0.6</priority>
                </url>
            @endforeach
        @endforeach
    @endforeach

    {{-- 4. Individual Questions (The "Long Tail" SEO) --}}
    @foreach ($posts as $post)
        @php
            $q_meta = question_meta_text($post);
            $slug = url_slug($post->article, $q_meta);
        @endphp
        <url>
            <loc>{{ url("/questions/{$post->id}/{$slug}") }}</loc>
            <priority>0.9</priority>
            <lastmod>{{ $post->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        </url>
    @endforeach

    {{-- 5. Paginated Main Feed --}}
    @php
        $totalQuestions = $posts->count();
        $perPage = 50;
        $pages = ceil($totalQuestions / $perPage);
    @endphp
    @for ($i = 1; $i <= $pages; $i++)
        <url>
            <loc>{{ url("/questions?page={$i}") }}</loc>
            <priority>0.4</priority>
        </url>
    @endfor

</urlset>