@extends('layout')

@section('content')
<div class="min-h-screen overflow-x-hidden">
    <div class="max-w-4xl mx-auto bg-white p-2 pr-4 md:p-8">
        
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 border-b pb-2 md:pb-6">
            <div class="text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 leading-tight">
                    {{ bnNum($year) }}তম বিসিএস {{ $category === 'Writing' ? 'লিখিত' : 'প্রিলিমিনারি' }} টেস্ট
                </h1>
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 mt-2 text-xs font-bold text-slate-500 uppercase">
                    <span>পূর্ণমান: {{ bnNum($posts->count()) }}</span>
                    <span class="hidden md:block w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span>সময়: {{ $category === 'Writing' ? '৩ ঘণ্টা' : '২ ঘণ্টা' }}</span>
                </div>
            </div>

            {{-- 1. Only show the Toggle if it's NOT a Writing category --}}
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

        <div class="space-y-0" id="questionsContainer">
            @forelse($posts as $index => $post)
            <div class="group border-b border-slate-100 pb-4 md:pb-8 last:border-0 pt-4 question-block">
                <div class="flex gap-2 md:gap-4">
                    
                    <span class="bn-number font-bold text-sm md:text-base text-slate-900 min-w-4 md:min-w-10 pt-0.5">
                        {{ bnNum($index + 1) }}
                    </span>
                    
                    <div class="flex-1 min-w-0">
                        <div class="text-base leading-relaxed text-slate-900 mb-3 md:mb-5 text-justify break-words">
                            {!! nl2br($post->article) !!}
                        </div>

                        {{-- 2. Only show the Options Grid if it's NOT a Writing category --}}
                        @if($category !== 'Writing')
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-2 gap-y-1.5 md:gap-4 text-base italic overflow-hidden">
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
</div>
@endsection