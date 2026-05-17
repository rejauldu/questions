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
</head>

<body class="bg-white text-slate-900 overflow-x-hidden antialiased">

    {{-- The Reading Container --}}
    <main id="reading-container">
        @yield('content')
    </main>

    {{-- Minimal Scripts --}}
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