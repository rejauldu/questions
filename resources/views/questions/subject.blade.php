@extends('layout')

@section('seo')
@php
    $title = $institution->name . ' Subjects - ExamDao';
    $description = 'Browse subjects for ' . $institution->name;
    $canonical = url()->current();
@endphp
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-2 md:py-8">
    <div class="max-w-6xl mx-auto px-2 md:px-4">
        
        {{-- Breadcrumb: Compact on mobile --}}
        <nav class="flex items-center gap-1.5 md:gap-2 text-[10px] md:text-xs mb-2 md:mb-4 text-gray-500 overflow-x-auto whitespace-nowrap pb-1 md:pb-2">
            <a href="{{ route('exam.show') }}" class="hover:text-primary-600">Exams</a>
            <svg class="w-2.5 h-2.5 md:w-3 md:h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
            <span class="font-semibold text-gray-800">{{ institution($institution->name) }}</span>
        </nav>

        {{-- Header: Adjusted font size and spacing --}}
        <div class="mb-3 md:mb-6">
            <h1 class="text-base md:text-2xl font-bold text-gray-800 flex items-center gap-1.5 md:gap-2">
                <span class="w-1 h-4 md:w-1.5 md:h-6 bg-primary-600 rounded-full"></span>
                Select Subject
            </h1>
            <p class="text-[9px] md:text-xs text-gray-500 mt-0.5 md:mt-1">Available subjects for {{ institution($institution->name) }}</p>
        </div>

        {{-- Subjects Grid: Reduced gap and padding --}}
        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-1.5 md:gap-3 mb-6 md:mb-10">
            @foreach($subjects as $sub)
                @php
                    $pattern = '/\s+(1st|2nd|১ম|২য়|পত্র)$/iu';
                    $genericName = trim(preg_replace($pattern, '', $sub->name));
                    $genericSlug = url_slug($genericName);
                @endphp
                
                <a href="{{ route('exam.show', [$institution->slug, $genericSlug]) }}" 
                   class="group flex flex-col items-center justify-center p-2 md:p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:border-primary-500 hover:ring-1 hover:ring-primary-500 transition-all duration-200">
                    
                    <div class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-600 font-bold text-[8px] md:text-[10px] group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors mb-1 md:mb-2">
                        {{ strtoupper(substr($genericName, 0, 2)) }}
                    </div>
        
                    <span class="text-[9px] md:text-xs font-bold text-gray-700 text-center leading-tight line-clamp-2">
                        {{ $genericName }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Questions Section Divider --}}
        <div class="flex items-center gap-2 md:gap-4 mb-3 md:mb-6">
            <span class="text-[9px] md:text-xs font-bold uppercase tracking-widest text-gray-400 whitespace-nowrap">All Questions</span>
            <div class="w-full h-px bg-gray-200"></div>
        </div>

        <div class="space-y-2 md:space-y-4">
            @if($posts->count())
                <div class="bg-white rounded-xl md:rounded-2xl border border-gray-100 shadow-sm p-1 md:p-4">
                    @include('partials.post-loop')
                </div>
            @else
                <div class="text-center py-8 md:py-12 bg-white rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-400 text-[10px] md:text-sm">No questions available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection