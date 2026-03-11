@extends('layout')

@section('seo')
@php
    $title = "BCS Preparation & Live Mock Tests | ExamDAO";
    $description = "Curated by BCS Cadre Officers. Access 50th to 10th BCS question banks and live model tests.";
@endphp
@endsection

@section('content')
<div class="bg-white min-h-screen antialiased">

    {{-- 1. Hero Section: Tightened for Mobile --}}
    <section class="relative bg-gradient-to-br from-slate-900 to-indigo-900 pt-8 pb-12 md:pt-16 md:pb-24 px-3 md:px-4 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-64 h-64 bg-indigo-500 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-500 rounded-full blur-3xl translate-x-1/3 translate-y-1/3"></div>
        </div>

        <div class="relative max-w-4xl mx-auto text-center">
            {{-- Live Stat Badge --}}
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 mb-4 md:mb-6">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-[9px] md:text-[10px] font-black text-indigo-100 uppercase tracking-widest">
                    542 Students practicing now
                </span>
            </div>

            <h1 class="text-2xl md:text-5xl font-black text-white leading-tight mb-4 md:mb-6 tracking-tighter">
                BECOME A <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-blue-200">CADRE</span>
            </h1>

            {{-- Optimized Search Bar: No border, minimal padding on mobile --}}
            <div class="relative max-w-xl mx-auto mb-6 md:mb-8">
                <div class="flex items-center bg-white rounded-xl md:rounded-2xl p-1 shadow-2xl ring-1 ring-slate-200 focus-within:ring-2 focus-within:ring-indigo-500 transition-all">
                    <div class="pl-3 md:pl-4 text-gray-400">
                        <x-icons.search class="w-4 h-4 md:w-5 md:h-5" />
                    </div>
                    <input type="text" 
                        placeholder="Search 50th BCS..." 
                        class="w-full py-2 md:py-4 px-2 md:px-4 text-xs md:text-sm font-medium focus:outline-none border-none ring-0 focus:ring-0 text-gray-700 bg-transparent" />
                    <button class="bg-indigo-600 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg md:rounded-xl font-bold text-[10px] md:text-xs uppercase tracking-widest hover:bg-indigo-700 transition-all">
                        Search
                    </button>
                </div>
            </div>

            <div class="flex flex-row justify-center gap-2 md:gap-4">
                <a href="{{ route('bcs.show', ['year' => '50']) }}" class="flex-1 md:flex-none px-4 md:px-8 py-3 md:py-4 bg-white text-indigo-900 font-black text-[10px] md:text-xs uppercase tracking-widest rounded-xl hover:scale-105 transition-transform">
                    Free Mock Test
                </a>
                <a href="#cadre-section" class="flex-1 md:flex-none px-4 md:px-8 py-3 md:py-4 bg-indigo-800/40 text-white border border-indigo-400/30 font-black text-[10px] md:text-xs uppercase tracking-widest rounded-xl hover:bg-indigo-800 transition-all">
                    Guidance
                </a>
            </div>
        </div>
    </section>

    {{-- 2. Cadre Section: Reduced vertical padding on mobile --}}
    <section id="cadre-section" class="py-8 md:py-16 px-3 md:px-4 bg-slate-50">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-3 md:gap-4 mb-6 md:mb-10">
                <div class="p-2 md:p-3 bg-indigo-600 rounded-xl md:rounded-2xl text-white shadow-lg">
                    <x-icons.users class="w-5 h-5 md:w-6 md:h-6" />
                </div>
                <div>
                    <h2 class="text-lg md:text-xl font-black text-slate-800 uppercase tracking-tight leading-none">BCS Cadre Curated</h2>
                    <p class="text-[10px] md:text-xs text-slate-500 font-medium">Expert tips & syllabus</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-6">
                <div class="bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl border border-slate-200 shadow-sm">
                    <span class="text-[9px] font-black text-indigo-600 uppercase mb-1 block">Blog</span>
                    <h3 class="font-bold text-sm md:text-base text-gray-800 mb-1">ক্যাডারদের প্রস্তুতি কৌশল</h3>
                    <p class="text-[10px] md:text-xs text-gray-500 mb-3">Time management & priority hacks.</p>
                    <a href="#" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest flex items-center gap-1">
                        Read Tips <x-icons.arrow-right class="w-2 h-2" />
                    </a>
                </div>
                <div class="bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl border border-slate-200 shadow-sm">
                    <span class="text-[9px] font-black text-green-600 uppercase mb-1 block">Clusters</span>
                    <h3 class="font-bold text-sm md:text-base text-gray-800 mb-1">বিষয়ভিত্তিক প্রস্তুতি</h3>
                    <p class="text-[10px] md:text-xs text-gray-500 mb-3">Master English, Math, & GK.</p>
                    <a href="#" class="text-[9px] font-black text-green-600 uppercase tracking-widest flex items-center gap-1">
                        Explore <x-icons.arrow-right class="w-2 h-2" />
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Question Bank Grid: Maximum usage of screen width on mobile --}}
    <section class="py-8 md:py-16 px-3 md:px-4">
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4">
                @foreach(range(50, 35) as $year)
                <a href="{{ route('bcs.show', ['year' => $year]) }}" 
                class="group relative bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl border border-slate-100 shadow-sm text-center transition-all {{ $year == 50 ? 'ring-2 ring-indigo-500 bg-indigo-50/30' : '' }}">
                    
                    <div class="text-3xl md:text-4xl font-black tracking-tighter {{ $year == 50 ? 'text-indigo-600' : 'text-slate-800' }}">
                        {{ $year }}<span class="text-xs font-bold lowercase">th</span>
                    </div>

                    <div class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-indigo-500 transition-colors">
                        BCS
                    </div>

                    @if($year == 50)
                        <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[7px] md:text-[8px] font-black px-1.5 md:px-2 py-0.5 rounded-full uppercase">New</span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 4. Community Section: Minimal bottom spacing --}}
    <section class="py-10 md:py-16 px-3 md:px-4 border-t border-slate-100">
        <div class="max-w-4xl mx-auto text-center">
            <h3 class="font-black text-slate-800 text-lg md:text-xl mb-2">Aspirant Community</h3>
            <p class="text-[10px] md:text-xs text-slate-500 mb-6 font-medium">Instant updates via Telegram.</p>
            
            <div class="flex flex-row justify-center gap-3 md:gap-6">
                <a href="#" class="flex items-center gap-2 px-4 md:px-6 py-3 bg-[#0088cc] text-white rounded-xl font-bold text-[10px] md:text-xs uppercase tracking-widest shadow-lg">
                    <x-icons.telegram class="w-3 h-3 md:w-4 md:h-4" /> Telegram
                </a>
                <a href="#" class="flex items-center gap-2 px-4 md:px-6 py-3 bg-[#25D366] text-white rounded-xl font-bold text-[10px] md:text-xs uppercase tracking-widest shadow-lg">
                    <x-icons.whatsapp class="w-3 h-3 md:w-4 md:h-4" /> WhatsApp
                </a>
            </div>
        </div>
    </section>

</div>
@endsection