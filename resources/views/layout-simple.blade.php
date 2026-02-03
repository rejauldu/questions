<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- SEO Data - Reusing your existing logic --}}
    @yield('seo')

    {{-- Browser UI Settings --}}
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-status-bar-style" content="black-translucent">

    <link rel="shortcut icon" href="/favicon.ico">

    {{-- Fonts: Serif for reading, Sans for UI --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Only Essential CSS --}}
    @vite(['resources/css/app.css'], 'build/front')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .reading-serif {
            font-family: 'Merriweather', serif;
        }
        /* Custom scrollbar for a clean look */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="bg-white text-slate-900 overflow-x-hidden antialiased">

    {{-- The Reading Container --}}
    <main id="reading-container">
        @yield('content')
    </main>

    {{-- Minimal Scripts --}}
    @stack('scripts')
    
</body>
</html>