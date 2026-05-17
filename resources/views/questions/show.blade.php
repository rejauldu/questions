@extends('layout')

@section('seo')
@inject('seoService', 'App\Services\SeoService')

@php
    /* =====================================================
     * 1. INITIAL LOGIC & VARIABLES
     * ===================================================== */
    $institution = institution($post->institution->name);
    $subject = subject($post->subject->name ?? null);
    $boardName = $post->board->name ?? '';
    $yearText = $post->year ?? '';
    $category = ($post->institution_id == 4 && $post->category == 'MCQ') ? 'Preli' : $post->category;

    $seo = $seoService->generate($post);
    $title = $seo['title'];
    $description = $seo['description'];

    $h1 = "{$institution} {$post->subject->name}";
    if ($category) $h1 .= " {$category}";
    if ($yearText || $boardName) {
        $h1 .= " (" . trim("{$boardName} {$yearText}") . ")";
    }

    $canonical = url()->current();

    /* --- Dynamic Share Image logic (Using the named route from ShareImageController) --- */
    $shareImage = route('share.image', $post->id);

    /* =====================================================
     * 2. CONTENT PREP FOR SCHEMA
     * ===================================================== */
    $questionText = trim(strip_tags($post->article));
    $mainQuestionText = $questionText ?: "Question for {$title}";

    $optionText = '';
    foreach (['a','b','c','d'] as $opt) {
        if (!empty(trim(strip_tags($post->$opt ?? '')))) {
            $optionText .= "\n" . strip_tags($post->$opt);
        }
    }

    $finalAnswer = '';
    if (strtoupper($post->category) === 'MCQ') {
        $finalAnswer = trim($post->ans);
    } elseif ($post->answer && $post->answer->text) {
        $finalAnswer = trim(strip_tags($post->answer->text));
    }

    if (!empty($post->explanation)) {
        $finalAnswer .= "\n\nব্যাখ্যা: " . trim(strip_tags($post->explanation));
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => array_values(array_filter([
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    $post->subject ? ['@type' => 'ListItem', 'position' => 2, 'name' => $subject, 'item' => url('/subject/'.slug($post->subject->name))] : null,
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $title, 'item' => $canonical],
                ])),
            ],
            [
                '@type' => 'QAPage',
                'mainEntity' => [
                    '@type' => 'Question',
                    'name' => \Illuminate\Support\Str::limit($mainQuestionText, 110),
                    'text' => trim($mainQuestionText . $optionText),
                    'answerCount' => 1,
                    'inLanguage' => 'bn',
                    'author' => ['@type' => 'Organization', 'name' => 'ExamDao', 'url' => 'https://examdao.com'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $finalAnswer, 'author' => ['@type' => 'Organization', 'name' => 'ExamDao']],
                ],
            ],
        ],
    ];
@endphp

{{-- ✅ SEO TAGS --}}
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $canonical }}">

{{-- Facebook Open Graph --}}
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:type" content="article">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $shareImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $shareImage }}">

<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection

@section('content')
<div class="min-h-screen bg-secondary-100">
    <div class="max-w-4xl mx-auto py-2 md:py-4 px-2 md:px-4">

        {{-- Admin Actions --}}
        <div class="hidden mb-3 md:mb-4 flex justify-end gap-1.5 md:gap-2 admin-actions">
            <a href="{{ route('svg.edit', $post->id) }}" class="px-3 md:px-4 py-1.5 md:py-2 bg-orange-500 text-white text-[10px] md:text-xs font-bold rounded-lg shadow-lg">Open SVG</a>
            <button id="save-clipboard-btn" data-post-id="{{ $post->id }}" class="px-3 md:px-4 py-1.5 md:py-2 bg-emerald-600 text-white text-[10px] md:text-xs font-bold rounded-lg shadow-lg">PASTE</button>
            <a href="{{ route('questions.next') }}" class="px-3 md:px-4 py-1.5 md:py-2 bg-blue-600 text-white text-[10px] md:text-xs font-bold rounded-lg shadow-lg">NEXT</a>
            <a href="{{ route('questions.edit', $post->id) }}" class="px-3 md:px-4 py-1.5 md:py-2 bg-red-600 text-white text-[10px] md:text-xs font-bold rounded-lg shadow-lg">EDIT</a>
        </div>

        {{-- Main Question Card --}}
        <div class="bg-white rounded-xl shadow-sm p-3 md:p-6 mb-4 md:mb-8 border-t-4 border-primary-600">
            <div class="flex justify-between items-start mb-3 md:mb-4 border-b pb-1 md:pb-3">
                <h1 class="text-sm md:text-lg font-bold text-secondary-900 leading-tight pr-1">
                    <span class="bg-secondary-200 text-secondary-600 px-1 py-0.5 rounded text-[10px] md:text-xs mr-1 md:mr-2">ID#{{ $post->id }}</span>
                    <span class="text-warning-700">{{ $h1 }}</span>
                </h1>
                
                @php
                    $full_copy_data = strip_tags($post->article) . "\n\n";
                    
                    if (!$post->url && !empty(trim(strip_tags($post->a)))) {
                        $full_copy_data .= "ক) " . strip_tags($post->a) . 
                                        "\nখ) " . strip_tags($post->b) . 
                                        "\nগ) " . strip_tags($post->c);
                        
                        // Check if 'd' exists and is not empty
                        if (!empty(trim(strip_tags($post->d)))) {
                            $full_copy_data .= "\nঘ) " . strip_tags($post->d);
                        }
                    }
                @endphp

                {{-- Updated Copy Section with Instructions --}}
                <div class="flex flex-col items-end gap-1 flex-shrink-0">
                    <div class="flex items-center gap-2 md:gap-3">
                        <button class="copy-btn flex items-center gap-1 text-secondary-400 hover:text-secondary-700 text-[10px] md:text-xs font-bold transition" 
                                data-copy="{{ $full_copy_data }}">
                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                            </svg>
                            <span>COPY</span>
                        </button>
                    </div>
                    
                    {{-- MS Word Guide Link --}}
                    <button onclick="document.getElementById('word-modal').classList.remove('hidden')" class="text-[9px] md:text-[10px] text-primary-500 hover:underline flex items-center gap-1">
                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        MS Word-এ লেখার নিয়ম
                    </button>
                </div>
                
                {{-- Simple Instruction Modal --}}
                <div id="word-modal" class="hidden cursor-pointer fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                    <div id="modal-content" class="cursor-default bg-white rounded-xl max-w-sm w-full p-5 shadow-2xl relative">
                        <button onclick="document.getElementById('word-modal').classList.add('hidden')" class="absolute top-3 right-3 text-secondary-400 hover:text-secondary-600">✕</button>
                        
                        <h3 class="font-bold text-secondary-900 mb-3 flex items-center gap-2">
                            <span class="p-1 bg-blue-100 rounded text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </span>
                            MS Word Writing Guide
                        </h3>
                        
                        <div class="space-y-3 text-xs md:text-sm text-secondary-700">
                            <div class="flex gap-2">
                                <span class="font-bold text-primary-600">১.</span>
                                <p>প্রথমে উপরের <b>COPY</b> বাটনে ক্লিক করুন।</p>
                            </div>
                            <div class="flex gap-2">
                                <span class="font-bold text-primary-600">২.</span>
                                <p>MS Word-এ গিয়ে <b>Ctrl + V</b> দিয়ে পেস্ট করুন।</p>
                            </div>
                            <div class="flex gap-2">
                                <span class="font-bold text-primary-600">৩.</span>
                                <div>
                                    <p>সমীকরণটি সিলেক্ট করে কিবোর্ডে <b>Alt</b> + <b>=</b> চাপুন।</p>
                                    <p class="mt-1 text-[10px] text-secondary-500 font-mono bg-secondary-100 p-1 rounded inline-block">Shortcut: Alt and equal key</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <span class="font-bold text-primary-600">৪.</span>
                                <p>এরপর ডানদিকের ড্রপডাউন থেকে <b>Professional</b> সিলেক্ট করলেই গণিত সুন্দর দেখাবে।</p>
                            </div>
                        </div>
                        
                        <button onclick="document.getElementById('word-modal').classList.add('hidden')" class="w-full mt-5 py-2 bg-secondary-900 text-white rounded-lg text-sm font-bold">বুঝেছি</button>
                    </div>
                </div>
            </div>
            
            <div class="text-sm md:text-base text-secondary-800 mb-3 md:mb-4 leading-relaxed text-justify">
                {!! smart_nl2br($post->article ?? "") !!}
            </div>

            @if ($post->image1)
                @foreach(['image1', 'image2', 'image3', 'image4'] as $imageField)
                    @if ($post->$imageField)
                        <div class="mb-3 md:mb-4 bg-white rounded-lg border border-secondary-200 overflow-hidden w-full shadow-sm">
                            <a href="{{ asset($post->$imageField) }}" target="_blank">
                                <img src="{{ asset($post->$imageField) }}" alt="{{ $h1 }}" class="w-full h-auto block" />
                            </a>
                        </div>
                    @endif
                @endforeach
            @else
                @if(!empty(trim(strip_tags($post->a))))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-secondary-700 text-xs md:text-sm">
                        @foreach(['a'=>'ক','b'=>'খ','c'=>'গ','d'=>'ঘ'] as $key => $label)
                            {{-- Check if the property exists and actually contains content --}}
                            @if(isset($post->$key) && !empty(trim(strip_tags($post->$key))))
                                <div class="p-2 rounded bg-secondary-50 border border-secondary-200">
                                    <b class="text-primary-500 mr-1">{{ $label }})</b> {!! $post->$key !!}
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endif
            
            <div class="mt-4 md:mt-6">
                <button id="answer-toggle" class="w-full text-left p-2.5 md:p-3 bg-primary-500 text-white text-sm md:text-base rounded-lg shadow-md flex justify-between items-center transition hover:bg-primary-600">
                    <span>উত্তর ও ব্যাখ্যা</span>
                    <x-icons.down-arrow id="toggle-icon" class="w-4 h-4 md:w-5 md:h-5" />
                </button>

                <div id="answer-content" class="hidden mt-2 md:mt-3 pt-2 md:pt-3 border-t">
                    @if ($post->ans)
                    <div class="p-3 md:p-4 bg-warning-50 text-warning-800 rounded-lg border border-warning-200 flex items-center mb-3 md:mb-4 shadow-sm">
                        <x-icons.tick-round class="w-5 h-5 md:w-6 md:h-6 text-warning-600 mr-2" />
                        <span class="text-lg md:text-xl font-black text-primary-700">
                            {{ (strtoupper($post->category) === 'MCQ') ? $post->ans : ($post->answer->text ?? "") }}
                        </span>
                    </div>
                    @endif

                    @if ($post->explanation)
                        <div class="pt-1 md:pt-2">
                            <h3 class="text-sm md:text-lg font-bold text-primary-700 mb-1 md:mb-2">ব্যাখ্যা</h3>
                            <div class="text-xs md:text-sm bg-primary-50 p-2.5 md:p-3 rounded-lg border border-primary-200 leading-relaxed">
                                {!! smart_nl2br($post->explanation) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Reading Mode CTA --}}
        <div class="mb-4 md:mb-8">
            <a href="{{ route('reading.mode', ['institution' => slug($institution), 'subject' => slug($post->subject->name), 'question' => $post->id, 'slug' => url_slug($post->article, $h1)]) }}" 
               class="group flex items-center justify-between p-3 md:p-4 bg-slate-900 rounded-xl border border-slate-700 shadow-lg transition-all duration-300">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-primary-600 rounded-lg md:rounded-xl flex items-center justify-center text-white group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-xs md:text-base leading-tight">অনুশীলন করুন</h3>
                        <p class="text-slate-400 text-[10px] md:text-xs">একটানা সকল প্রশ্নের সমাধান পড়ুন</p>
                    </div>
                </div>
                <div class="text-primary-400 font-bold text-[10px] md:text-xs uppercase tracking-widest bg-slate-800 py-1.5 px-2.5 md:px-3 rounded-lg group-hover:bg-primary-600 group-hover:text-white transition-all">Start</div>
            </a>
        </div>

        {{-- Resource Details Table --}}
        <div class="mb-6 md:mb-8 border rounded-xl overflow-hidden bg-white shadow-sm border-secondary-200">
            <div class="bg-secondary-50 px-3 md:px-4 py-1.5 md:py-2 border-b text-[9px] md:text-[10px] font-bold text-secondary-500 uppercase">Resource Details</div>
            <table class="w-full text-[11px] md:text-sm text-left text-secondary-700">
                <tbody class="divide-y divide-secondary-100">
                    @foreach(['Exam' => $institution, 'Subject' => $subject, 'Chapter' => $post->chapter ?? null, 'Board' => $post->board->name ?? null, 'Year' => $post->year ?? null] as $label => $value)
                        @if($value)
                        <tr>
                            <td class="px-3 md:px-4 py-2 md:py-2.5 font-semibold bg-secondary-50/50 w-1/3">{{ $label }}</td>
                            <td class="px-3 md:px-4 py-2 md:py-2.5">{{ $value }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        @include('partials.discussion-section', ['post' => $post, 'comments' => $post->comments])
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wordModal = document.getElementById('word-modal');
        
        // Only run if the modal exists on this specific page
        if (wordModal) {
            // Close modal when clicking the dark background
            wordModal.addEventListener('click', (e) => {
                // If target is exactly the background div, hide it
                if (e.target === wordModal) {
                    wordModal.classList.add('hidden');
                }
            });

            // Close on 'Escape' key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !wordModal.classList.contains('hidden')) {
                    wordModal.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush