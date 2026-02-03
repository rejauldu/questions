@php 
    $xmlHeader = '<?xml version="1.0" encoding="UTF-8"?>'; 
@endphp
{!! $xmlHeader !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- 1. Fixed Public Pages --}}
    <url><loc>{{ route('home') }}</loc><priority>1.0</priority></url>
    <url><loc>{{ route('questions.list') }}</loc><priority>0.8</priority></url>
    <url><loc>{{ route('chatbot') }}</loc><priority>0.8</priority></url>
    <url><loc>{{ route('about') }}</loc><priority>0.5</priority></url>
    <url><loc>{{ route('contact') }}</loc><priority>0.5</priority></url>

    {{-- 2. Hierarchical Exam Routes --}}
    @foreach ($institutions as $institution)
        {{-- Level 1: Institution Root --}}
        <url>
            <loc>{{ url('/exam/' . $institution->slug) }}</loc>
            <priority>0.9</priority>
        </url>

        {{-- Level 2: Filtered Subjects --}}
        @php
            $filteredSubjects = $subjects->where('institution_id', $institution->id);
        @endphp

        @foreach ($filteredSubjects as $subject)
            @php
                $subYears = $posts->where('institution_id', $institution->id)
                                  ->where('subject_id', $subject->id)
                                  ->pluck('year')
                                  ->unique()
                                  ->filter();
            @endphp

            @if($subYears->isNotEmpty())
                {{-- Subject Index --}}
                <url>
                    <loc>{{ url('/exam/' . $institution->slug . '/' . $subject->slug) }}</loc>
                    <priority>0.7</priority>
                </url>

                {{-- Subject + Year --}}
                @foreach ($subYears as $year)
                    <url>
                        <loc>{{ url('/exam/' . $institution->slug . '/' . $subject->slug . '/' . $year) }}</loc>
                        <priority>0.6</priority>
                    </url>
                @endforeach
            @endif
        @endforeach
    @endforeach

    {{-- 3. Individual Questions (Bengali Clean Up) --}}
    @foreach ($posts as $post)
        @php
            $q_meta = question_meta_text($post);
            $slug = url_slug($post->article, $q_meta);
            
            // Generate the full URL
            $fullUrl = route('questions.show', ['question' => $post->id, 'slug' => $slug]);
            
            // urldecode makes the Bengali characters readable in the XML file 
            // instead of the %E0 symbols, which is better for some validators.
            $cleanUrl = urldecode($fullUrl);
        @endphp
        <url>
            <loc>{{ $cleanUrl }}</loc>
            <priority>0.9</priority>
            <lastmod>{{ $post->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        </url>
    @endforeach

</urlset>