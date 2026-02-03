@extends('layout')

@section('content')
<div class="min-h-screen overflow-x-hidden">
    <div class="max-w-4xl mx-auto bg-white p-2 pr-4 md:p-8">
        
        <div class="text-center mb-6 md:mb-10">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900">
                {{ bnBoard($posts->first()->board->name ?? 'All') }} বোর্ড {{ bnNum($year) }}
            </h1>
            
            @if($posts->count() > 0)
            <div class="mt-2 md:mt-6 flex flex-col items-center gap-4">
                <div class="inline-block px-4 py-1 border-2 border-slate-900 font-bold text-lg text-slate-900">
                    বিষয়: {{ $posts->first()->subject->name ?? 'আবশ্যিক' }} 
                    <span class="uppercase">({{ $category }})</span>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-4">
                    @php 
                        $targetCategory = (strtolower($category) === 'mcq') ? 'CQ' : 'MCQ';
                    @endphp

                    {{-- Show Answer Toggle - Only for MCQ --}}
                    @if(strtolower($category) === 'mcq')
                    <div class="flex items-center justify-center gap-3 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-600 uppercase tracking-tighter">উত্তর দেখুন</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="answerToggle" class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div id="questionsContainer">
            @forelse($posts as $index => $post)
            <div class="group border-b border-slate-100 pb-2 md:pb-4 last:border-0 question-block">
                <div class="flex gap-2 md:gap-6">
                    
                    <span class="bn-number font-bold text-sm md:text-base text-slate-900 min-w-4 md:min-w-10 pt-0.5">
                        {{ bnNum($index + 1) }}
                    </span>
                    
                    <div class="flex-1 min-w-0">
                        <div class="text-base leading-relaxed text-slate-900 mb-3 md:mb-5 text-justify break-words">
                            {!! nl2br($post->article) !!}
                        </div>

                        @if(strtolower($category) === 'cq')
                        {{-- CQ Layout --}}
                        <div class="grid text-base text-slate-800 space-y-1">
                            <div class="flex gap-1"><span>ক.</span> <span class="break-words">{!! $post->a !!}</span></div>
                            <div class="flex gap-1"><span>খ.</span> <span class="break-words">{!! $post->b !!}</span></div>
                            <div class="flex gap-1"><span>গ.</span> <span class="break-words">{!! $post->c !!}</span></div>
                            <div class="flex gap-1"><span>ঘ.</span> <span class="break-words">{!! $post->d !!}</span></div>
                        </div>
                        @else
                        {{-- MCQ Grid with Highlighting --}}
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-2 gap-y-1.5 md:gap-4 text-base text-slate-800 italic overflow-hidden">
                            @foreach(['a' => 'ক', 'b' => 'খ', 'c' => 'গ', 'd' => 'ঘ'] as $key => $label)
                                @php 
                                    $isCorrect = (strtolower($post->ans) == $label);
                                @endphp
                                <div class="option-item flex gap-1 transition-all duration-200">
                                    <span class="option-label font-bold {{ $isCorrect ? 'correct-label text-slate-400' : 'text-slate-400' }}">
                                        {{ $label }}.
                                    </span>
                                    <span class="break-words">{!! $post->$key !!}</span>
                                </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="mt-2 md:mt-4 flex justify-end">
                            <a href="{{ route('questions.show', ['question' => $post->id, 'slug' => url_slug($post->article)]) }}" 
                               class="text-[10px] font-bold tracking-widest uppercase text-slate-400 md:text-slate-300 group-hover:text-indigo-600 transition-colors">
                                View Solution —
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
    </div>

    @if($nextSet)
    <div class="mt-4 md:mt-8 mb-10 flex flex-col items-center">
        <div class="bg-white border-2 border-slate-900 p-1 hover:shadow-none rounded-sm">
            <a href="{{ $nextSet->institution_id == 2 
                        ? route('hsc.show', ['subject' => $nextSet->subject->name, 'year' => $nextSet->year, 'category' => $nextSet->category ?? 'all']) 
                        : route('bcs.show', ['year' => $nextSet->year]) }}" 
               class="block px-8 py-3 bg-slate-900 text-white font-bold text-sm text-center">
                {{ bnBoard($nextSet->board->name ?? '') }} ({{ bnNum($nextSet->year) }}) - {{ $nextSet->category ?? 'General' }} দেখুন →
            </a>
        </div>
    </div>
    @endif
</div>
@endsection