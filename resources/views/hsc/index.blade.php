@extends('layout')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-8 md:pb-12">
    
    {{-- 1. Compact Header: Reduced padding --}}
    <section class="bg-indigo-700 pt-4 md:pt-8 pb-10 md:pb-14 px-4 rounded-b-[24px] md:rounded-b-[32px] shadow-lg">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-xl md:text-4xl font-black text-white mb-1 md:mb-2 tracking-tighter uppercase">
                HSC <span class="text-indigo-300">Exam</span> Prep
            </h1>
            
            {{-- Minimal Live Stats --}}
            <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-sm px-2.5 py-1 rounded-full border border-white/10">
                <span class="flex h-1.5 w-1.5">
                    <span class="animate-ping absolute inline-flex h-1.5 w-1.5 rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-500"></span>
                </span>
                <p class="text-white text-[9px] font-bold uppercase tracking-widest">
                    <span class="text-green-300">৫৪২</span> Online
                </p>
            </div>
        </div>
    </section>

    {{-- 2. Primary Cards: Tightened gap and -mt --}}
    <div class="max-w-4xl mx-auto px-3 md:px-4 -mt-6 md:-mt-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
            
            {{-- English Card --}}
            <a href="{{ route('hsc.show', ['subject' => 'english']) }}" 
               class="group relative overflow-hidden bg-white p-4 md:p-6 rounded-xl md:rounded-2xl shadow-md border border-indigo-50 active:scale-[0.98] transition-all">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-indigo-600 rounded-lg md:rounded-xl flex items-center justify-center shrink-0 shadow-indigo-200 shadow-lg">
                        <span class="text-lg md:text-xl">🇬🇧</span>
                    </div>
                    <div>
                        <h2 class="text-lg md:text-xl font-black text-slate-900 leading-none">English</h2>
                        <p class="text-slate-500 text-[9px] md:text-xs font-bold mt-1 uppercase tracking-tight">Suggestions & Q/A</p>
                    </div>
                </div>
                <div class="mt-3 md:mt-4 flex justify-between items-center border-t border-slate-50 pt-2 md:pt-3">
                    <span class="text-[9px] md:text-[10px] font-black text-indigo-600 uppercase tracking-widest">Prepare Now →</span>
                    <span class="text-[9px] md:text-[10px] text-slate-300 font-bold uppercase">A+ Focused</span>
                </div>
            </a>

            {{-- ICT Card --}}
            <a href="{{ route('hsc.show', ['subject' => 'ict']) }}" 
               class="group relative overflow-hidden bg-slate-900 p-4 md:p-6 rounded-xl md:rounded-2xl shadow-md active:scale-[0.98] transition-all">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-indigo-500 rounded-lg md:rounded-xl flex items-center justify-center shrink-0 shadow-indigo-900/50 shadow-lg">
                        <span class="text-lg md:text-xl">💻</span>
                    </div>
                    <div>
                        <h2 class="text-lg md:text-xl font-black text-white leading-none">ICT</h2>
                        <p class="text-slate-400 text-[9px] md:text-xs font-bold mt-1 uppercase tracking-tight">MCQ & Written</p>
                    </div>
                </div>
                <div class="mt-3 md:mt-4 flex justify-between items-center border-t border-white/5 pt-2 md:pt-3">
                    <span class="text-[9px] md:text-[10px] font-black text-indigo-400 uppercase tracking-widest">Mock Test →</span>
                    <span class="text-[9px] md:text-[10px] text-slate-600 font-bold uppercase">All Boards</span>
                </div>
            </a>

        </div>
    </div>

    {{-- 3. Secondary Text --}}
    <div class="max-w-4xl mx-auto px-6 mt-6 md:mt-8">
        <p class="text-center text-slate-400 text-[8px] md:text-[10px] font-bold uppercase tracking-[0.2em]">
            Select a subject to practice
        </p>
    </div>
</div>
@endsection