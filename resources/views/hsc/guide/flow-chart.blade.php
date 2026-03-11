@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-3 py-4 sm:py-8 bg-white shadow-sm rounded-xl sm:rounded-2xl border border-slate-100">
    <nav class="text-[10px] sm:text-[11px] font-bold mb-2 sm:mb-4 uppercase tracking-widest px-1 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <span class="text-indigo-600">HSC English 1st</span>
            <span class="text-slate-300">/</span>
            <span class="text-gray-400">Question 2</span>
        </div>
    </nav>
    
    <header class="mb-4 sm:mb-8 px-1">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 leading-tight mb-1 uppercase italic underline decoration-indigo-500 decoration-4 underline-offset-4">
            Flow Chart Analysis
        </h1>
    </header>

    <div class="mb-6 sm:mb-8 bg-indigo-50 border-l-4 border-indigo-600 rounded-r-xl p-4 sm:p-6 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="bg-indigo-600 text-white p-1.5 rounded-lg shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-indigo-900 font-black text-sm sm:text-base uppercase tracking-wide mb-1 italic underline underline-offset-2">কৌশল: Flow-chart তৈরির নিয়ম</h4>
                <p class="text-indigo-800 text-xs sm:text-[13px] leading-relaxed font-medium">
                    Flow-chart এ কখনো পূর্ণাঙ্গ বাক্য ব্যবহার করা যাবে না; সবসময় <b>Phrase</b> ব্যবহার করতে হবে। সাধারণত <b>verb+ing</b>, <b>Noun Phrase</b> বা <b>Infinitive</b> দিয়ে শুরু করা সবচেয়ে ভালো।
                </p>
                <p class="text-indigo-700 text-[10px] mt-2 font-bold uppercase tracking-wider italic bg-white/50 inline-block px-2 py-0.5 rounded">Marks: $1 \times 5 = 5$</p>
            </div>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 sm:grid-cols-3 gap-3 px-1">
        <div class="bg-rose-50 border border-rose-100 p-3 rounded-xl shadow-sm">
            <p class="text-[9px] font-black text-rose-600 uppercase mb-1">বর্জনীয়</p>
            <p class="text-[11px] sm:text-xs font-bold text-rose-900 leading-tight">পূর্ণাঙ্গ বাক্য ও ফুলস্টপ ব্যবহার করা যাবে না।</p>
        </div>
        <div class="bg-sky-50 border border-sky-100 p-3 rounded-xl shadow-sm">
            <p class="text-[9px] font-black text-sky-600 uppercase mb-1">আবশ্যক</p>
            <p class="text-[11px] sm:text-xs font-bold text-sky-900 leading-tight">বক্সের নম্বর ও তীর (Arrow) অবশ্যই দিতে হবে।</p>
        </div>
        <div class="bg-amber-50 border border-amber-100 p-3 rounded-xl shadow-sm">
            <p class="text-[9px] font-black text-amber-600 uppercase mb-1">পরামর্শ</p>
            <p class="text-[11px] sm:text-xs font-bold text-amber-900 leading-tight">সব বক্সে একই গ্রামাটিক্যাল স্টাইল বজায় রাখুন।</p>
        </div>
    </div>

    <section class="mb-8 px-1">
        <h2 class="text-slate-900 mb-3 font-bold text-sm sm:text-base uppercase tracking-tight">2. Read the following text and make a flow chart:</h2>
        <div class="p-4 sm:p-6 bg-slate-50 border border-slate-200 rounded-2xl italic text-slate-700 leading-relaxed text-sm sm:text-base font-serif shadow-inner relative">
            <div class="absolute -top-3 left-6 bg-white px-3 py-0.5 border border-slate-200 text-[10px] font-bold uppercase text-slate-400 rounded-full">Stimulus Passage</div>
            <p>
                "Education not only enriches us with knowledge, abilities and skills, it also teaches us values... students receiving good education develop self-respect but also learn to respect others; they know the importance of honesty and learn to trust others; they develop compassion and fellow feeling and become aware of the need to protect the environment."
            </p>
        </div>
    </section>

    <section class="mb-12">
        <div class="flex flex-col sm:flex-row flex-wrap items-center justify-center gap-3 sm:gap-4 text-center">
            @php
                $steps = [
                    ['n' => '1', 't' => 'Enriches us with knowledge', 'active' => true],
                    ['n' => '2', 't' => 'Teaching us values', 'active' => false],
                    ['n' => '3', 't' => 'Developing self-respect', 'active' => false],
                    ['n' => '4', 't' => 'Learning to respect others', 'active' => false],
                    ['n' => '5', 't' => 'Learning to trust others', 'active' => false],
                    ['n' => '6', 't' => 'Protecting the environment', 'active' => false]
                ];
            @endphp

            @foreach($steps as $index => $step)
                <div class="w-full sm:w-40 p-4 rounded-xl shadow-sm transition-all border-2 {{ $step['active'] ? 'bg-indigo-600 border-indigo-600' : 'bg-white border-slate-200 hover:border-indigo-400' }}">
                    <p class="text-[10px] font-black mb-1 {{ $step['active'] ? 'text-indigo-200' : 'text-slate-400' }}">BOX {{ $step['n'] }}</p>
                    <p class="text-xs font-bold leading-tight {{ $step['active'] ? 'text-white' : 'text-slate-800' }}">{{ $step['t'] }}</p>
                </div>

                @if(!$loop->last)
                    <div class="hidden sm:block text-indigo-300 font-black scale-150">→</div>
                    <div class="sm:hidden text-indigo-300 text-xl">↓</div>
                @endif

                @if($index == 2)
                    <div class="hidden lg:block w-full h-1"></div>
                @endif
            @endforeach
        </div>
    </section>

    <section class="bg-slate-900 rounded-xl overflow-hidden shadow-2xl border-t-4 border-indigo-600">
        <div class="bg-indigo-600 px-4 py-2 text-white flex justify-between items-center">
            <h3 class="font-black uppercase tracking-widest text-[10px] sm:text-xs italic">Answer Script</h3>
            <span class="text-[9px] bg-white/20 px-2 py-0.5 rounded font-bold">Flow Chart Layout</span>
        </div>
        <div class="p-6 font-mono text-xs sm:text-sm text-slate-300">
            <p class="font-bold text-center underline text-white mb-6 italic tracking-widest">Ans to the Q. no-2</p>
            
            <div class="border-2 border-dashed border-slate-700 p-4 rounded-xl">
                <div class="grid grid-cols-1 gap-4 items-center max-w-xs mx-auto text-center">
                    @foreach($steps as $step)
                        <div class="border border-slate-600 p-2 rounded">
                            {{ $step['n'] }}. {{ $step['t'] }}
                        </div>
                        @if(!$loop->last)
                            <div class="text-slate-600">↓</div>
                        @endif
                    @endforeach
                </div>
            </div>
            <p class="mt-4 text-[10px] text-center text-slate-500 italic">*পরীক্ষায় বক্সগুলো নিচে নিচে (Vertical) অথবা পাশাপাশি (Horizontal) উভয়ভাবেই আঁকা যাবে।</p>
        </div>
    </section>
</div>
@endsection