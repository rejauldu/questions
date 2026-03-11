@extends('layout')

@section('content')
<div class="min-h-screen overflow-x-hidden bg-gray-50/50">
    <div class="max-w-4xl mx-auto bg-white p-2 pr-4 md:p-8 shadow-sm border-x border-gray-100">
        
        {{-- 1. Header Section --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 border-b pb-2 md:pb-6">
            <div class="text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 leading-tight">
                    @if($isSubjectView)
                        {{ $posts->first()->subject->name ?? 'বিষয়ভিত্তিক' }} স্পেশাল প্রস্তুতি
                    @else
                        {{ bnNum($year) }}তম বিসিএস {{ $category === 'Writing' ? 'লিখিত' : 'প্রিলিমিনারি' }} টেস্ট
                    @endif
                </h1>
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 mt-2 text-xs font-bold text-slate-500 uppercase">
                    <span>পূর্ণমান: ২০০</span>
                    <span class="hidden md:block w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span>সময়: {{ $category === 'Writing' ? '৩ ঘণ্টা' : '২ ঘণ্টা' }}</span>
                </div>
            </div>

            @if($category !== 'Writing')
            <div class="flex items-center justify-center gap-3 bg-slate-50 px-4 py-2 rounded-lg border border-slate-200">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-tighter">উত্তর দেখুন</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="answerToggle" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>
            @endif
        </div>

        {{-- 2. Questions Container --}}
        <div class="space-y-0" id="questionsContainer">
            @forelse($posts as $index => $post)
            <div class="group border-b border-slate-100 pb-2 md:pb-6 last:border-0 pt-8 question-block">
                <div class="flex gap-2 md:gap-4 items-start">
                    
                    <div class="relative flex flex-col items-start pt-0.5">
                        @if($isSubjectView)
                            <span class="absolute -top-4 left-0 text-[9px] md:text-[10px] font-black text-indigo-500 uppercase tracking-tighter whitespace-nowrap opacity-80">
                                {{ bnNum($post->year) }}তম বিসিএস
                            </span>
                        @endif
                        
                        <span class="font-bold text-base md:text-xl text-slate-900 leading-tight">
                            {{ bnNum(($posts->currentPage() - 1) * $posts->perPage() + $index + 1) }}।
                        </span>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="text-base md:text-xl leading-tight text-slate-900 mb-4 md:mb-6 text-justify break-words font-medium">
                            {!! nl2br($post->article) !!}
                        </div>

                        @if($category !== 'Writing')
                        {{-- Logic to determine grid layout based on option length --}}
                        @php
                            $optionB = strip_tags($post->b);
                            $wordCount = count(explode(' ', $optionB));
                            // If more than 4 words, use single col on mobile and 2 cols on desktop
                            $gridClasses = ($wordCount > 3) 
                                ? "grid-cols-1 lg:grid-cols-2" 
                                : "grid-cols-2 lg:grid-cols-4";
                        @endphp

                        <div class="grid {{ $gridClasses }} gap-x-2 gap-y-2 md:gap-4 text-base italic">
                            @foreach(['a' => 'ক', 'b' => 'খ', 'c' => 'গ', 'd' => 'ঘ'] as $key => $label)
                                @php 
                                    $isCorrect = (strtolower($post->ans) == $label);
                                @endphp
                                <div class="option-item flex gap-1 transition-all duration-200 text-slate-800">
                                    <span class="option-label font-bold {{ $isCorrect ? 'correct-label text-slate-400' : 'text-slate-400' }}">
                                        {{ $label }}.
                                    </span>
                                    <span class="break-words">{!! $post->$key !!}</span>
                                </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="mt-3 md:mt-5 flex justify-end border-t border-dashed border-slate-50 pt-2">
                            <a href="{{ route('questions.show', ['question' => $post->id, 'slug' => url_slug($post->article)]) }}" 
                               class="text-[10px] font-bold tracking-widest uppercase text-slate-400 group-hover:text-indigo-600 transition-colors">
                                উত্তর দেখুন —
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-20 italic text-slate-400">
                কোনো প্রশ্ন খুঁজে পাওয়া যায়নি।
            </div>
            @endforelse
        </div>

        {{-- 3. Pagination Links --}}
        <div class="mt-8 mb-8">
            {{ $posts->links() }}
        </div>

        {{-- 4. Next/Flow Button --}}
        @if($nextSet)
            <div class="mt-6 border-t pt-8">
                <p class="text-center text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">আরও অনুশীলন করুন</p>
                <a href="{{ $isSubjectView ? route('bcs.show', ['year' => $nextSet->subject->slug]) : route('bcs.show', ['year' => $nextSet->year]) }}" 
                   class="flex items-center justify-center gap-2 p-5 bg-slate-900 text-white rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-lg hover:shadow-indigo-200">
                    @if($isSubjectView)
                        অন্য বিষয় দেখুন
                    @else
                        {{ bnNum($nextSet->year) }}তম বিসিএস প্রশ্ন
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection