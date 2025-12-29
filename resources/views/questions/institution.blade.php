@extends('layout')

@section('seo')
@php
    $title = 'Exam Questions by Institution - ExamDao';
    $description = 'Browse questions by institution. Access SSC, HSC, Admission, NU, and BCS exam questions.';
    $canonical = url()->current();
@endphp
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-4 md:py-8">
    <div class="max-w-6xl mx-auto px-4">
        
        {{-- Breadcrumb Navigation (Simple) --}}
        <nav class="flex items-center gap-2 text-xs mb-4 text-gray-500 pb-2">
            <span class="font-semibold text-gray-800 uppercase tracking-wider">All Exams</span>
        </nav>

        {{-- Header Section --}}
        <div class="mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-primary-600 rounded-full"></span>
                Select Institution
            </h1>
            <p class="text-xs text-gray-500 mt-1">Choose an institution to see specific question banks</p>
        </div>

        {{-- Institutions Grid --}}
        {{-- For 4 items: 2 columns on mobile, 4 columns on desktop --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-10">
            @foreach($institutions as $inst)
                <a href="{{ route('exam.show', $inst->slug) }}" 
                   class="group flex flex-col items-center justify-center p-5 bg-white rounded-xl border border-gray-200 shadow-sm hover:border-primary-500 hover:ring-1 hover:ring-primary-500 transition-all duration-200">
                    
                    {{-- Avatar with initials --}}
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-50 text-gray-600 font-bold text-lg group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors mb-3">
                        {{ strtoupper(substr($inst->name, 0, 1)) }}
                    </div>

                    <span class="text-sm md:text-base font-bold text-gray-700 text-center leading-tight">
                        {{ institution($inst->name) }}
                    </span>
                    
                    <span class="text-[10px] text-gray-400 mt-2 uppercase tracking-tighter opacity-0 group-hover:opacity-100 transition-opacity">
                        Enter Vault
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Content Divider --}}
        <div class="flex items-center gap-4 mb-6">
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400 whitespace-nowrap">Global Question Feed</span>
            <div class="w-full h-px bg-gray-200"></div>
        </div>

        {{-- Questions Feed --}}
        <div class="space-y-4">
            @if($posts->count())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-1 md:p-4">
                    @include('partials.post-loop')
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-300">
                    <p class="text-gray-400 text-sm">No recent questions found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection