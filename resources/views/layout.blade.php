<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ============================= --}}
    {{-- Dynamic SEO (Title + Meta)   --}}
    {{-- ============================= --}}

    @php
        $seo_title = $title ?? config('app.name', 'Axamination');
        $seo_description = $description ?? 'Access thousands of past board exam questions and verified solutions for SSC, HSC, and Admission Tests. Start practicing with the definitive question archive today!';
        $seo_image = $image ?? url('/images/logo.webp');
        $seo_canonical = $canonical ?? url()->current();
    @endphp

    {{-- Standard Title --}}
    <title>{{ $seo_title }}</title>

    {{-- Basic SEO --}}
    <meta name="description" content="{{ $seo_description }}">
    <link rel="canonical" href="{{ $seo_canonical }}">

    {{-- OpenGraph --}}
    <meta property="og:title" content="{{ $seo_title }}">
    <meta property="og:description" content="{{ $seo_description }}">
    <meta property="og:image" content="{{ $seo_image }}">
    <meta property="og:url" content="{{ $seo_canonical }}">
    <meta property="og:type" content="website">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo_title }}">
    <meta name="twitter:description" content="{{ $seo_description }}">
    <meta name="twitter:image" content="{{ $seo_image }}">

    {{-- ============================= --}}
    {{-- Browser + Mobile UI Settings  --}}
    {{-- ============================= --}}

    {{-- Theme colors for all browsers --}}
    <meta name="theme-color" content="#4338ca"> {{-- Blue (change if needed) --}}
    <meta name="msapplication-navbutton-color" content="#4338ca">

    {{-- iOS Safari Status Bar Color --}}
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-capable" content="yes">

    {{-- Android Chrome --}}
    <meta name="mobile-web-app-capable" content="yes">

    {{-- Prevent telephone/email auto-detection (optional) --}}
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">

    {{-- ============================= --}}
    {{-- App Icons, Favicons, Manifest --}}
    {{-- ============================= --}}

    {{-- Standard favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

    {{-- Apple Touch Icon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

    {{-- Fallback icon --}}
    <link rel="shortcut icon" href="/favicon.ico">

    {{-- ============================= --}}
    {{-- Additional SEO from views     --}}
    {{-- ============================= --}}
    @yield('seo')

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