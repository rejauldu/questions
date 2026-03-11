@extends('layout')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-12">
    
    {{-- 1. Compact Header --}}
    <section class="bg-indigo-700 pt-8 pb-14 px-4 rounded-b-[32px] shadow-lg">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-2xl md:text-4xl font-black text-white mb-2 tracking-tighter uppercase">
                HSC <span class="text-indigo-300">Exam</span> Preparation
            </h1>
            
            {{-- Minimal Live Stats --}}
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full border border-white/10">
                <span class="flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <p class="text-white text-[10px] font-bold uppercase tracking-widest">
                    <span class="text-green-300">৫৪২</span> Students Online
                </p>
            </div>
        </div>
    </section>

    {{-- 2. Primary Cards: Visible without scrolling on mobile --}}
    <div class="max-w-4xl mx-auto px-4 -mt-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            {{-- English Card --}}
            <a href="{{ route('hsc.show', ['subject' => 'english']) }}" 
               class="group relative overflow-hidden bg-white p-6 rounded-2xl shadow-md border border-indigo-50 active:scale-[0.98] transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center shrink-0 shadow-indigo-200 shadow-lg">
                        <span class="text-xl">🇬🇧</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 leading-none">English</h2>
                        <p class="text-slate-500 text-xs font-bold mt-1 uppercase tracking-tight">Suggestions & Board Q/A</p>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center border-t border-slate-50 pt-3">
                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Prepare Now →</span>
                    <span class="text-[10px] text-slate-300 font-bold uppercase">A+ Focused</span>
                </div>
            </a>

            {{-- ICT Card --}}
            <a href="{{ route('hsc.show', ['subject' => 'ict']) }}" 
               class="group relative overflow-hidden bg-slate-900 p-6 rounded-2xl shadow-md active:scale-[0.98] transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center shrink-0 shadow-indigo-900/50 shadow-lg">
                        <span class="text-xl">💻</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-white leading-none">ICT</h2>
                        <p class="text-slate-400 text-xs font-bold mt-1 uppercase tracking-tight">MCQ & Written Solutions</p>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center border-t border-white/5 pt-3">
                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Start Mock Test →</span>
                    <span class="text-[10px] text-slate-600 font-bold uppercase">All Boards</span>
                </div>
            </a>

        </div>
    </div>

    {{-- 3. Secondary Text (Optional) --}}
    <div class="max-w-4xl mx-auto px-6 mt-8">
        <p class="text-center text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em]">
            Select a subject to begin your practice
        </p>
    </div>
</div>
@endsection