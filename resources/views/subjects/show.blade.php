@extends('layout')

@section('seo')
@php
    /*
    |--------------------------------------------------------------------------
    | 🔥 CONSOLIDATED SEO & LOGIC BLOCK
    |--------------------------------------------------------------------------
    */
    $institution = $subject->institution->name ?? 'Exam';
    $institutionRaw = strtolower($institution);
    $subjectName = $subject->bangla ?? $subject->name ?? 'Subject';
    $subjectFull = ($subject->bangla && $subject->name) 
        ? "{$subject->name} ({$subject->bangla})" 
        : $subjectName;

    $category = request('category', 'Questions');
    $board = request('board');
    $year = request('year');

    // 1. Resolve Board Name from availableFilters
    $boardName = '';
    if ($board && isset($availableFilters)) {
        $filterMatch = $availableFilters->firstWhere('board_id', $board);
        $boardName = $filterMatch->board->name ?? '';
    }

    // 2. Format Institution Display (Add "Board" suffix for HSC)
    $isBoard = in_array($institutionRaw, ['dhaka','chittagong','rajshahi','comilla','barisal','sylhet', 'dinajpur', 'jessore', 'mymensingh']);
    $displayInst = ($isBoard && !Str::contains($institutionRaw, 'board')) ? "{$institution} Board" : $institution;

    // 3. Smart Year / BCS Ordinal Handling
    $yearText = '';
    if ($institutionRaw === 'bcs' && $year) {
        $yearInt = (int)$year;
        $suffix = 'th';
        if (!in_array(($yearInt % 100), [11, 12, 13])) {
            $suffix = match ($yearInt % 10) {
                1 => 'st', 2 => 'nd', 3 => 'rd', default => 'th'
            };
        }
        $yearText = "{$yearInt}{$suffix} BCS";
    } elseif ($year) {
        $yearText = $year;
    }

    // 4. Build Meta Tags
    $title = "{$displayInst} {$subjectName}";
    if ($category) $title .= " {$category}";
    if ($yearText || $boardName) {
        $title .= " (" . trim("{$boardName} {$yearText}") . ")";
    }
    
    $title .= " | ExamDao";

    $description = \Illuminate\Support\Str::limit(
        "Practice {$displayInst} {$subjectName} {$category} questions " . 
        ($yearText ? "from {$yearText} " : "") . 
        "with answers and explanations on ExamDao.", 
        155
    );

    $keywords = implode(', ', array_filter([$institution, $subjectName, $category, $year, 'mcq', 'cq', 'ExamDao']));
    $canonical = url()->current();

    // 5. Schema Data
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $title,
        'description' => $description,
        'itemListElement' => []
    ];

    foreach ($posts as $index => $post) {
        $schema['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => route('questions.show', $post->id),
            'name' => \Illuminate\Support\Str::limit(strip_tags($post->article), 80)
        ];
    }
@endphp

{{-- ✅ SEO TAGS --}}
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<link rel="canonical" href="{{ $canonical }}">

{{-- ✅ Open Graph --}}
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $canonical }}">

{{-- ✅ Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection

@section('content')
<div class="min-h-screen bg-[#fcfaf7] text-slate-800 antialiased font-sans">
    
    {{-- Header Section --}}
    <div class="bg-white border-b border-slate-200/60">
        <header class="pt-2 md:pt-6 pb-1 md:pb-6 max-w-6xl mx-auto px-6 text-center">
            <nav class="max-w-4xl mx-auto px-6 gap-2 text-[11px] mb-4 text-slate-400 uppercase tracking-widest font-bold hidden md:flex">
                <a href="{{ route('home') }}" class="hover:text-emerald-600 transition-colors">Home</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"></path></svg>
                <span class="text-slate-500">{{ $institution }}</span>
            </nav>
            
            <h1 class="text-2xl md:text-4xl font-black text-slate-900 tracking-tight mb-4">
                {{ $subject->bangla }}
            </h1>

            {{-- Category Toggle --}}
            @if($showToggle)
                <div class="flex justify-center mb-4">
                    <div class="inline-flex bg-slate-100 p-1 rounded-2xl border border-slate-200 shadow-inner">
                        @foreach($availableCategories as $type)
                            <a href="{{ request()->fullUrlWithQuery(['category' => $type]) }}" 
                               class="px-6 py-1.5 rounded-xl text-xs font-black transition-all {{ request('category', $availableCategories->first()) == $type ? 'bg-white text-emerald-700 shadow-md' : 'text-slate-400 hover:text-slate-600' }}">
                                {{ $type }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- NEW: Chapter Toggle --}}
            @if(isset($availableChapters) && $availableChapters->isNotEmpty())
                <div class="flex justify-center mb-2 overflow-x-auto no-scrollbar">
                    <div class="inline-flex bg-slate-100 p-1 rounded-2xl border border-slate-200">
                        {{-- "All" Option for Chapters --}}
                        <a href="{{ request()->fullUrlWithQuery(['chapter' => null]) }}" 
                           class="px-4 py-1 rounded-xl text-xs font-bold transition-all {{ !request()->has('chapter') ? 'bg-white text-emerald-700 shadow-md' : 'text-slate-400 hover:text-slate-600' }}">
                           All Ch
                        </a>
                        @foreach($availableChapters as $chap)
                            <a href="{{ request()->fullUrlWithQuery(['chapter' => $chap]) }}" 
                               class="px-4 py-1 rounded-xl text-xs font-bold transition-all {{ request('chapter') == $chap ? 'bg-white text-emerald-700 shadow-md' : 'text-slate-400 hover:text-slate-600' }}">
                                Ch-{{ $chap }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </header>
    </div>

    {{-- STICKY FILTER BAR --}}
    <div class="sticky top-0 bg-white/90 backdrop-blur-xl z-40 border-b border-slate-200/60 shadow-sm">
    
        {{-- Indicators (Mobile Only) --}}
        {{-- Left & Right Gradient Strips --}}
        <div class="absolute left-0 top-0 bottom-0 w-3 bg-gradient-to-r from-slate-200 to-transparent md:hidden z-30"></div>
        <div class="absolute right-0 top-0 bottom-0 w-3 bg-gradient-to-l from-slate-200 to-transparent md:hidden z-30"></div>
        
        {{-- Top & Bottom Thin Borders --}}
        <div class="absolute top-0 left-0 w-full h-[2px] bg-slate-300 md:hidden z-30"></div>
        <div class="absolute bottom-0 left-0 w-full h-[2px] bg-slate-300 md:hidden z-30"></div>

        <div class="max-w-6xl mx-auto px-4 relative flex items-center">
            
            {{-- Desktop Left Arrow --}}
            <button id="left-arrow" class="hidden md:flex absolute left-0 z-50 w-8 h-8 items-center justify-center bg-white border border-slate-200 rounded-full shadow-md text-slate-500 hover:text-emerald-600 opacity-70 hover:opacity-100 hover:scale-150 transition-all">‹</button>

            {{-- Scrollable Container --}}
            <div id="board-scroll" class="flex-1 overflow-x-auto scroll-smooth no-scrollbar py-3 md:px-8">
                <div class="flex gap-3">
                    {{-- Search Input --}}
                    <div class="shrink-0">
                        <input type="text" id="filter-search" placeholder="Search..." 
                            class="w-32 px-3 py-1.5 text-sm border border-slate-200 rounded-sm focus:ring-1 focus:ring-emerald-500 outline-none">
                    </div>

                    @foreach($availableFilters as $filter)
                        <a href="?board={{ $filter->board_id }}&year={{ $filter->year }}" 
                            data-bangla="{{ $filter->board->bangla ?? '' }} {{ bnNum($filter->year) }}"
                            data-english="{{ $filter->board->name ?? '' }} {{ $filter->year }}"
                            data-banglish="{{ $filter->board->banglish ?? '' }} {{ $filter->year }}"
                            class="filter-item px-4 py-1.5 rounded-sm text-sm font-bold transition-all shrink-0 border {{ (request('board') == $filter->board_id && request('year') == $filter->year) ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-slate-200 text-slate-500' }}">
                            {{ $filter->board->bangla ?? '' }} {{ bnNum($filter->year) }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Desktop Right Arrow --}}
            <button id="right-arrow" class="hidden md:flex absolute right-0 z-50 w-8 h-8 items-center justify-center bg-white border border-slate-200 rounded-full shadow-md text-slate-500 hover:text-emerald-600 opacity-70 hover:opacity-100 hover:scale-150 transition-all">›</button>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="max-w-4xl mx-auto px-1 py-1 md:py-4">
        @include('partials.post-loop-full', ['posts' => $posts, 'subject' => $subject])
        <div class="mt-2">
            {{ $posts->links() }}
        </div>
        {{-- (Footer SEO section remains unchanged) --}}
    </main>
</div>
@endsection