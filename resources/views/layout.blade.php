<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        /* =====================================================
         * 1. SEO & META LOGIC
         * ===================================================== */
        
        // Check if we are on the question show page to avoid meta conflicts
        $isQuestionPage = request()->routeIs('questions.show');

        // Base SEO values
        $seo_title = $title ?? config('app.name', 'ExamDao');
        $seo_description = $description
            ?? 'Access thousands of past board exam questions and verified solutions for SSC, HSC, and Admission Tests. Start practicing today!';
        $seo_canonical = $canonical ?? url()->current();

        // Default fallback image for home/category pages
        $defaultImage = url('/images/og-default.webp');
    @endphp

    {{-- Standard Title --}}
    <title>{{ $seo_title }}</title>

    {{-- Basic SEO --}}
    <meta name="description" content="{{ $seo_description }}">
    <link rel="canonical" href="{{ $seo_canonical }}">

    {{-- =====================================================
         2. OPENGRAPH / SOCIAL META
         ===================================================== --}}

    {{-- 
        If it's NOT a question page, render default site-wide meta. 
        If it IS a question page, layout will stay silent and let 
        the child view (@section('seo')) handle it.
    --}}
    @if(!$isQuestionPage)
        <meta property="og:title" content="{{ $seo_title }}">
        <meta property="og:description" content="{{ $seo_description }}">
        <meta property="og:url" content="{{ $seo_canonical }}">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ $defaultImage }}">
        
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $seo_title }}">
        <meta name="twitter:description" content="{{ $seo_description }}">
        <meta name="twitter:image" content="{{ $defaultImage }}">
    @endif

    {{-- =====================================================
         3. CHILD VIEW SEO INJECTION
         ===================================================== --}}
    @yield('seo')

    {{-- =====================================================
         4. BROWSER & MOBILE UI
         ===================================================== --}}
    <meta name="theme-color" content="#4338ca">
    <meta name="msapplication-navbutton-color" content="#4338ca">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">

    {{-- Favicons --}}
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="shortcut icon" href="/favicon.ico">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Global Config --}}
    <script>
        window.AUTH_STATUS_URL = "{{ url('/auth/status') }}";
        window.LOGIN_URL = "{{ url('/login') }}";
        window.SUBJECTS_API_URL = "{{ route('api.posts.subjects-by-institution') }}";
        window.CURRENT_INSTITUTION_ID = "{{ request('institution_id') }}";
        window.CURRENT_SUBJECT = "{{ request('subject_id') }}";
    </script>

    {{-- Asset Loading (Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'build/front')
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    @include('components.header')

    <main id="main-content">
        @yield('content')
    </main>

    @include('components.footer')
    
    {{-- Stacked Scripts from Child Views --}}
    @stack('scripts')

    {{-- =====================================================
         5. MATHJAX CONFIGURATION
         ===================================================== --}}
    <script>
        window.MathJax = {
            tex: {
              inlineMath: [['$', '$'], ['\\(', '\\)']],
              processEscapes: true
            },
            chtml: {
              // This is the key setting for Bengali
              mtextInheritFont: true, 
              fontCache: 'global'
            }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js" defer></script>

</body>
</html>