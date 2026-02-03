@php $xmlHeader = '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
{!! $xmlHeader !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Fixed Pages --}}
    <url><loc>{{ route('home') }}</loc><priority>1.0</priority></url>
    <url><loc>{{ route('questions.index') }}</loc><priority>0.8</priority></url>
    <url><loc>{{ route('chatbot') }}</loc><priority>0.8</priority></url>
    <url><loc>{{ route('about') }}</loc><priority>0.5</priority></url>
    <url><loc>{{ route('contact') }}</loc><priority>0.5</priority></url>

    {{-- Hierarchical: Institutions → Subjects → Categories --}}
    @foreach ($institutions as $institution)
        <url>
            <loc>{{ url('/exam/' . $institution->slug) }}</loc>
            <priority>0.9</priority>
        </url>

        @php $filteredSubjects = $subjects->where('institution_id', $institution->id); @endphp
        @foreach ($filteredSubjects as $subject)
            {{-- Determine categories based on institution_id --}}
            @php
                if (in_array($institution->id, [1, 2])) {
                    $categories = ['CQ', 'MCQ'];
                } else if (in_array($institution->id, [3, 4])) {
                    $categories = ['MCQ', 'Writing'];
                } else {
                    $categories = []; // fallback, just in case
                }
            @endphp

            @if(!empty($categories))
                {{-- Subject Index --}}
                <url>
                    <loc>{{ url('/exam/' . $institution->slug . '/' . $subject->slug) }}</loc>
                    <priority>0.7</priority>
                </url>

                {{-- Categories --}}
                @foreach ($categories as $category)
                    <url>
                        <loc>{{ url('/exam/' . $institution->slug . '/' . $subject->slug . '/' . strtolower($category)) }}</loc>
                        <priority>0.6</priority>
                    </url>
                @endforeach
            @endif
        @endforeach
    @endforeach
</urlset>