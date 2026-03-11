@extends('layout')

@section('seo')
@php
    $title = $institution->name . ' Subjects - ExamDao';
    $description = 'Browse subjects for ' . $institution->name;
    $canonical = url()->current();
@endphp
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-4 md:py-8">
    <div class="max-w-6xl mx-auto px-4">
        
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs mb-4 text-gray-500 overflow-x-auto whitespace-nowrap pb-2">
            <a href="{{ route('exam.show') }}" class="hover:text-primary-600">Exams</a>
            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
            <span class="font-semibold text-gray-800">{{ institution($institution->name) }}</span>
        </nav>

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-primary-600 rounded-full"></span>
                Select Subject
            </h1>
            <p class="text-xs text-gray-500 mt-1">Available subjects for {{ institution($institution->name) }}</p>
        </div>

        {{-- Subjects Grid --}}
        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-2 md:gap-3 mb-10">
            @foreach($subjects as $sub)
                @php
                    // Define the terms to remove only if they are at the end
                    // \b ensures we match whole words, and $ ensures they are at the end
                    // 'i' at the end makes it case-insensitive
                    $pattern = '/\s+(1st|2nd|১ম|২য়|পত্র)$/iu';

                    // preg_replace handles the logic
                    $genericName = trim(preg_replace($pattern, '', $sub->name));

                    // Generate the generic slug
                    $genericSlug = url_slug($genericName);
                @endphp
                
                <a href="{{ route('exam.show', [$institution->slug, $genericSlug]) }}" 
                   class="group flex flex-col items-center justify-center p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:border-primary-500 hover:ring-1 hover:ring-primary-500 transition-all duration-200">
                    
                    <div class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-600 font-bold text-[10px] group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors mb-2">
                        {{ strtoupper(substr($genericName, 0, 2)) }}
                    </div>
        
                    <span class="text-[10px] md:text-xs font-bold text-gray-700 text-center leading-tight line-clamp-2">
                        {{ $genericName }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Questions Section --}}
        <div class="flex items-center gap-4 mb-6">
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400 whitespace-nowrap">All Questions</span>
            <div class="w-full h-px bg-gray-200"></div>
        </div>

        <div class="space-y-4">
            @if($posts->count())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-1 md:p-4">
                    @include('partials.post-loop')
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-300">
                    <p class="text-gray-400 text-sm">No questions available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection