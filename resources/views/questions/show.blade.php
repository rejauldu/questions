@extends('layout')

@section('seo')
@php
    $institution = institution($post->institution->name);
    $subject = subject($post->subject->name ?? null);
    /* =====================================================
     * 1. SEO META DATA
     * ===================================================== */
    $seoParts = [];

    if (!empty($post->institution->name)) {
        $seoParts[] = $institution;
    }
    if (!empty($post->subject->name)) {
        $seoParts[] = $subject;
    }
    if (!empty($post->class)) {
        $seoParts[] = ordinal_suffix($post->class) . ' year';
    }
    if (!empty($post->board->name)) {
        $seoParts[] = $post->board->name . ' Board';
    }
    if (!empty($post->year)) {
        $seoParts[] = $post->year;
    }

    $h1 = implode(' ', $seoParts);
    $title = Str::limit($h1, 36, '...') . ' Questions | ExamDao';

    $questionText = trim(strip_tags($post->article));
    $description = Str::limit(
        $questionText . ' | ' . implode(', ', $seoParts),
        155,
        '...'
    );

    $canonical = url()->current();

    /* =====================================================
     * 2. QUESTION TEXT + OPTIONS
     * ===================================================== */
    $mainQuestionText = $questionText ?: "Question for {$h1}";

    $optionText = '';
    foreach (['a','b','c','d'] as $opt) {
        if (!empty(trim(strip_tags($post->$opt ?? '')))) {
            $optionText .= "\n" . strip_tags($post->$opt);
        }
    }

    /* =====================================================
     * 3. ANSWER + EXPLANATION
     * ===================================================== */
    $finalAnswer = '';

    if (strtoupper($post->category) === 'MCQ') {
        $finalAnswer = trim($post->ans);
    } elseif ($post->answer && $post->answer->text) {
        $finalAnswer = trim(strip_tags($post->answer->text));
    }

    if (!empty($post->explanation)) {
        $finalAnswer .= "\n\nব্যাখ্যা: " . trim(strip_tags($post->explanation));
    }

    if (empty($finalAnswer)) {
        $finalAnswer = 'The correct answer is provided above with explanation.';
    }

    /* =====================================================
     * 4. IMAGES
     * ===================================================== */
    $images = [];
    foreach (['image1','image2','image3','image4'] as $img) {
        if (!empty($post->$img)) {
            $images[] = asset($post->$img);
        }
    }

    /* =====================================================
     * 5. SCHEMA GRAPH (GOOGLE SAFE)
     * ===================================================== */
    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            // Breadcrumbs
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => array_values(array_filter([
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => url('/'),
                    ],
                    $post->subject ? [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => $subject,
                        'item' => url('/subject/'.slug($post->subject->name)),
                    ] : null,
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $h1,
                        'item' => $canonical,
                    ],
                ])),
            ],

            // QAPage
            [
                '@type' => 'QAPage',
                'mainEntity' => [
                    '@type' => 'Question',
                    'name' => Str::limit($mainQuestionText, 110),
                    'text' => trim($mainQuestionText . $optionText),
                    'answerCount' => 1,
                    'inLanguage' => 'bn',
                    'author' => [
                        '@type' => 'Organization',
                        'name' => 'ExamDao',
                        'url' => 'https://examdao.com',
                    ],
                    'about' => array_values(array_filter([
                        $institution, // HSC
                        $subject,          // Bangla 2nd Paper
                        $post->board ? $post->board->name . ' Board' : null,
                        $post->year ?? null,
                    ])),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $finalAnswer,
                        'author' => [
                            '@type' => 'Organization',
                            'name' => 'ExamDao',
                        ],
                    ],
                ],
            ],
        ],
    ];

    if (!empty($images)) {
        $schema['@graph'][1]['mainEntity']['image'] = $images;
    }
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection

@section('content')
<div class="min-h-screen bg-secondary-100">
    <div class="max-w-4xl mx-auto py-2 sm:py-4 px-2 sm:px-4">

        <div class="bg-white rounded-xl shadow-xl p-5 md:p-6 mb-8 border-t-4 border-primary-600">
            <div class="flex justify-between items-start mb-4 border-b pb-3">
                <h1 class="text-sm font-bold text-secondary-900 leading-tight pr-4">
                    <span class="text-warning-700 p-1 px-2 rounded-md">
                        {{ $h1 }}
                    </span>
                </h1>
                
                @php
                    $full_copy_data = strip_tags($post->article) . "\n\n";
                    if (!$post->url && !empty(trim(strip_tags($post->a)))) {
                        $full_copy_data .= "ক) " . strip_tags($post->a) . "\nখ) " . strip_tags($post->b) . "\nগ) " . strip_tags($post->c) . "\nঘ) " . strip_tags($post->d);
                    }
                @endphp
                <button id="copy-question-btn" class="copy-btn flex items-center gap-1 text-secondary-500 hover:text-secondary-700 text-xs font-medium transition duration-150 ease-in-out flex-shrink-0" data-copy="{{ $full_copy_data }}">
                    <x-icons.copy /> Copy
                </button>
            </div>
            
            <p class="text-base text-secondary-800 mb-4 leading-relaxed text-justify">
                {!! nl2br($post->article ?? "") !!}
            </p>

            @if ($post->image1)
                @foreach(['image1', 'image2', 'image3', 'image4'] as $imageField)
                    @if ($post->$imageField)
                        <div class="mb-4 bg-white rounded-lg border border-secondary-200 overflow-hidden w-full shadow-sm">
                            <a href="{{ asset($post->$imageField) }}" target="_blank">
                                <img src="{{ asset($post->$imageField) }}" alt="{{ $h1 }} - Part {{ $loop->iteration }}" class="w-full h-auto block" />
                            </a>
                        </div>
                    @endif
                @endforeach
            @else
                @if(!empty(trim(strip_tags($post->a))))
                <div class="space-y-2 text-secondary-700 text-sm">
                    @foreach(['a'=>'ক','b'=>'খ','c'=>'গ','d'=>'ঘ'] as $key => $label)
                        @if(!empty(trim(strip_tags($post->$key))))
                        <p class="p-2 rounded bg-secondary-50 border border-secondary-200">
                            <span class="font-bold text-primary-500 mr-1">{{ $label }})</span> {!! $post->$key !!}
                        </p>
                        @endif
                    @endforeach
                </div>
                @endif
            @endif
            
            <div class="mt-6">
                <button id="answer-toggle" class="w-full text-left p-3 bg-primary-500 text-white text-base rounded-lg shadow-md flex justify-between items-center">
                    <span>উত্তর ও ব্যাখ্যা</span>
                    <x-icons.down-arrow id="toggle-icon" />
                </button>

                <div id="answer-content" class="hidden mt-3 pt-3 border-t">
                    @php $haveAnswer = $post->ans || ($post->answer && $post->answer->text); @endphp
                    @if ($haveAnswer)
                    <div class="p-4 bg-warning-100 text-warning-800 rounded-lg border border-warning-400 flex items-center shadow-sm mb-4 relative">
                        <x-icons.tick-round class="w-6 h-6 text-warning-600 mr-2" />
                        <span class="text-xl font-extrabold text-primary-700">
                            {{ (strtoupper($post->category) === 'MCQ') ? $post->ans : ($post->answer->text ?? "") }}
                        </span>
                    </div>
                    @endif

                    @if ($post->explanation)
                        <div class="pt-2">
                            <h3 class="text-lg font-bold text-primary-700 mb-2">ব্যাখ্যা</h3>
                            <div class="text-sm bg-primary-50 p-4 rounded-lg border border-primary-200">
                                {!! nl2br($post->explanation) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Start Reading Mode Action --}}
        <div class="mb-8">
            <a href="{{ route('reading.mode', [
                    'institution' => slug(institution($post->institution->name)), 
                    'subject' => slug($post->subject->name), 
                    'id' => $post->id, 
                    'slug' => url_slug($post->article, question_meta_text($post))
                ]) }}" 
               class="group relative flex items-center justify-between p-4 bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl border border-slate-700 shadow-lg hover:shadow-primary-500/20 transition-all duration-300">
                
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center text-white shadow-inner group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-sm sm:text-base leading-tight">অনুশীলন করুন</h3>
                        <p class="text-slate-400 text-xs mt-0.5">একটানা সকল প্রশ্নের সমাধান পড়ুন</p>
                    </div>
                </div>
        
                <div class="flex items-center gap-2 text-primary-400 font-bold text-xs uppercase tracking-widest bg-slate-700/50 py-2 px-3 rounded-lg group-hover:bg-primary-600 group-hover:text-white transition-all">
                    Start <x-icons.down-arrow class="w-4 h-4 -rotate-90" />
                </div>
        
                {{-- Subtle background decoration --}}
                <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-white/5 to-transparent rounded-r-2xl pointer-events-none"></div>
            </a>
        </div>

        {{-- Fact Table --}}
        <div class="mb-8 border rounded-xl overflow-hidden bg-white shadow-sm border-secondary-200">
            <div class="bg-secondary-50 px-4 py-2 border-b text-[10px] font-bold text-secondary-500 uppercase">Resource Details</div>
            <table class="w-full text-xs sm:text-sm text-left text-secondary-700">
                <tbody class="divide-y">
                    @foreach(['Exam' => $institution, 'Subject' => $subject, 'Chapter' => $post->chapter ?? null, 'Board' => $post->board->name ?? null, 'Year' => $post->year ?? null] as $label => $value)
                        @if($value)
                        <tr>
                            <td class="px-4 py-2.5 font-semibold bg-secondary-50/50 w-1/3">{{ $label }}</td>
                            <td class="px-4 py-2.5">{{ $value }}</td>
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