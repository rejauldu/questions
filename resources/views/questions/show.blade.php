@extends('layout')

@section('seo')
@php
    // Collect question meta parts in the same sequence as question_meta_text()
    $seoParts = [];

    // Institution (first part)
    if (!empty($post->institution->name)) {
        $seoParts[] = explode('/', $post->institution->name)[0];
    }

    // Subject
    if (!empty($post->subject->name)) {
        $seoParts[] = $post->subject->name;
    }

    // Class with ordinal
    if (!empty($post->class)) {
        $seoParts[] = ordinal_suffix($post->class) . ' year';
    }

    // Board
    if (!empty($post->board->name)) {
        $seoParts[] = $post->board->name;
    }

    // Year
    if (!empty($post->year)) {
        $seoParts[] = $post->year;
    }

    // SEO Keyword/Title: combine all parts and append site branding
    $title = implode(' - ', $seoParts) . ' - Questions & Solutions | ExamDao';

    // SEO Description: include full question text + meta parts
    $questionText = strip_tags($post->article);
    $questionText = mb_strlen($questionText) > 150 ? mb_substr($questionText, 0, 147) . '...' : $questionText;

    $description = "$questionText | " . implode(', ', $seoParts) . ". Access chapter-wise questions, past papers, model tests, and verified solutions for SSC, HSC, Admission, NU & BCS exams on ExamDao.";

    // OG Image
    $image = url('/images/og-home.webp');

    // Canonical URL
    $canonical = url()->current();
@endphp
@endsection


@section('content')

<div class="min-h-screen bg-secondary-100">
    <div class="max-w-4xl mx-auto py-2 sm:py-4 px-2 sm:px-4">

        <div class="bg-white rounded-xl shadow-xl p-5 md:p-6 mb-8 border-t-4 border-primary-600">
            <div class="flex justify-between items-start mb-4 border-b pb-3">
                
                {{-- Meta Data using helper --}}
                <h1 class="text-sm font-bold text-secondary-900 leading-tight pr-4">
                    <span class="text-warning-700 p-1 px-2 rounded-md">
                        {{ question_meta_text($post) }}
                    </span>
                </h1>
                
                {{-- Copy Question Button --}}
                @php
                    $full_copy_data = strip_tags($post->article) . "\n\n";
                    // Only append options to copy data if they are text-based (no image)
                    if (!$post->url) {
                        $full_copy_data .= "ক) " . strip_tags($post->a) . "\n" 
                                         . "খ) " . strip_tags($post->b) . "\n" 
                                         . "গ) " . strip_tags($post->c) . "\n" 
                                         . "ঘ) " . strip_tags($post->d);
                    }
                @endphp
                <button id="copy-question-btn" class="copy-btn flex items-center gap-1 text-secondary-500 hover:text-secondary-700 text-xs font-medium transition duration-150 ease-in-out flex-shrink-0" data-copy="{{ $full_copy_data }}">
                    <x-icons.copy />
                    Copy
                </button>
            </div>
            
            {{-- Question Content --}}
            <div class="text-base text-secondary-800 mb-4 leading-relaxed">
                {!! $post->article !!}
            </div>

            {{-- Image Display (Full Width) --}}
            @if ($post->url)
                <div class="mb-4 bg-white rounded-lg border border-secondary-200 overflow-hidden w-full">
                    <img src="{{ asset($post->url) }}" 
                         alt="Question Diagram/Image" 
                         class="w-full h-auto block" />
                </div>
            @else
                {{-- Only show text options if there is no image --}}
                <div class="space-y-2 text-secondary-700 text-sm">
                    <p class="p-2 rounded bg-secondary-50 border border-secondary-200">
                        <span class="font-bold text-primary-500 min-w-4 inline-block mr-1">ক)</span> {!! $post->a !!}
                    </p>
                    <p class="p-2 rounded bg-secondary-50 border border-secondary-200">
                        <span class="font-bold text-primary-500 min-w-4 inline-block mr-1">খ)</span> {!! $post->b !!}
                    </p>
                    <p class="p-2 rounded bg-secondary-50 border border-secondary-200">
                        <span class="font-bold text-primary-500 min-w-4 inline-block mr-1">গ)</span> {!! $post->c !!}
                    </p>
                    <p class="p-2 rounded bg-secondary-50 border border-secondary-200">
                        <span class="font-bold text-primary-500 min-w-4 inline-block mr-1">ঘ)</span> {!! $post->d !!}
                    </p>
                </div>
            @endif
            
            {{-- Answer Section --}}
            <div class="mt-6">
                <button id="answer-toggle" class="w-full text-left p-3 bg-primary-500 text-white font-bold text-base rounded-lg shadow-md hover:bg-primary-600 transition duration-200 flex justify-between items-center">
                    <span>Show Answer & Explanation</span>
                    <x-icons.down-arrow id="toggle-icon" />
                </button>

                <div id="answer-content" class="hidden mt-3 pt-3 border-t border-secondary-200">
                    
                    <div class="p-4 bg-warning-100 text-warning-800 rounded-lg border border-warning-400 font-semibold flex items-center shadow-sm mb-4 relative">
                        <x-icons.tick-round class="w-6 h-6 text-warning-600 flex-shrink-0" />
                        <div class="flex items-baseline w-full justify-between">
                            <div class="flex items-center text-sm">
                                <span class="uppercase tracking-wider mr-2">Correct Answer:</span> 
                                <span class="text-xl font-extrabold text-primary-700">{{ $post->answer }}</span>
                            </div>
                        </div>
                        
                        <button class="copy-btn copy-answer-btn absolute top-2 right-2 flex items-center gap-1 text-warning-700 hover:text-primary-700 text-xs font-medium transition duration-150 flex-shrink-0" data-copy="{{ $post->answer }}">
                            <x-icons.copy />
                            Copy Answer
                        </button>
                    </div>

                    @if ($post->explanation ?? false)
                    <div class="pt-2">
                        <h3 class="text-lg font-bold text-primary-700 mb-2">Expert Explanation</h3>
                        <div class="text-secondary-700 leading-relaxed text-sm bg-primary-50 p-4 rounded-lg border border-primary-200 shadow-inner">
                            {!! $post->explanation !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Discussion Section --}}
        @include('partials.discussion-section', ['post' => $post, 'comments' => $post->comments])
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    if (window.MathJax) {
        MathJax.typesetPromise();
    }
});
</script>
@endpush