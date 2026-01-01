<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ============================= --}}
    {{-- Dynamic SEO (Title + Meta)   --}}
    {{-- ============================= --}}

    @php
        // Base SEO values
        $seo_title = $title ?? config('app.name', 'ExamDao');
        $seo_description = $description
            ?? 'Access thousands of past board exam questions and verified solutions for SSC, HSC, and Admission Tests. Start practicing with the definitive question archive today!';
        $seo_canonical = $canonical ?? url()->current();

        // Collect question images if $post exists
        $ogImages = [];

        if (isset($post)) {
            foreach (['image1', 'image2', 'image3', 'image4'] as $field) {
                if (!empty($post->$field)) {
                    $ogImages[] = url($post->$field);
                }
            }
        }

        // Default fallback image
        $defaultImage = url('/images/og-default.webp');

        // Primary image (Twitter + first OG image)
        $seo_image = $ogImages[0] ?? ($image ?? $defaultImage);
    @endphp

    {{-- Standard Title --}}
    <title>{{ $seo_title }}</title>

    {{-- Basic SEO --}}
    <meta name="description" content="{{ $seo_description }}">
    <link rel="canonical" href="{{ $seo_canonical }}">

    {{-- ============================= --}}
    {{-- OpenGraph Meta                --}}
    {{-- ============================= --}}

    <meta property="og:title" content="{{ $seo_title }}">
    <meta property="og:description" content="{{ $seo_description }}">
    <meta property="og:url" content="{{ $seo_canonical }}">
    <meta property="og:type" content="website">

    @if (!empty($ogImages))
        @foreach ($ogImages as $img)
            <meta property="og:image" content="{{ $img }}">
        @endforeach
    @else
        <meta property="og:image" content="{{ $seo_image }}">
    @endif

    {{-- ============================= --}}
    {{-- Twitter Card                  --}}
    {{-- ============================= --}}

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo_title }}">
    <meta name="twitter:description" content="{{ $seo_description }}">
    <meta name="twitter:image" content="{{ $seo_image }}">

    {{-- ============================= --}}
    {{-- Browser + Mobile UI Settings  --}}
    {{-- ============================= --}}

    <meta name="theme-color" content="#4338ca">
    <meta name="msapplication-navbutton-color" content="#4338ca">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">

    {{-- ============================= --}}
    {{-- Favicons & App Icons          --}}
    {{-- ============================= --}}

    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="shortcut icon" href="/favicon.ico">

    {{-- ============================= --}}
    {{-- Extra SEO from child views    --}}
    {{-- ============================= --}}
    @yield('seo')

    {{-- CSRF Token for JavaScript --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Global Config for External JS --}}
    <script>
        window.AUTH_STATUS_URL = "{{ url('/auth/status') }}";
        window.LOGIN_URL = "{{ url('/login') }}";
        window.SUBJECTS_API_URL = "{{ route('api.posts.subjects-by-institution') }}";
        
        // Pass current request data for dropdown persistence
        window.CURRENT_INSTITUTION_ID = "{{ request('institution_id') }}";
        window.CURRENT_SUBJECT = "{{ request('subject_id') }}";
    </script>

    {{-- CSS + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'build/front')
</head>

<body class="bg-gray-50 text-gray-800">

    @include('components.header')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    @stack('scripts')

</body>
</html>