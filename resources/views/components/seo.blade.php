<title>{{ $title ?? 'ICT4Today' }}</title>
<meta name="description" content="{{ $description ?? 'Your default description' }}">
<meta name="keywords" content="{{ $keywords ?? 'ICT, Laravel, Vue, Tailwind' }}">
<meta name="author" content="Md Rejaul Karim">

<!-- Open Graph / Facebook -->
<meta property="og:title" content="{{ $title ?? 'ICT4Today' }}">
<meta property="og:description" content="{{ $description ?? 'Your default description' }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $image ?? asset('images/og-image.png') }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title ?? 'ICT4Today' }}">
<meta name="twitter:description" content="{{ $description ?? 'Your default description' }}">
<meta name="twitter:image" content="{{ $image ?? asset('images/og-image.png') }}">
