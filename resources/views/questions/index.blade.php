@extends('layout')
@section('seo')
@php
    $query = request('q'); // User search text

    // If user entered a search query, emphasize it in the title
    $title = $query
        ? "\"$query\" Questions & Solutions - ExamDao"
        : "Search - ExamDao";

    // Description: naturally include the keyword first
    $description = $query
        ? "Explore questions related to \"$query\" from SSC, HSC, Admission, NU, and BCS exams on ExamDao. Access chapter-wise solutions, past questions, and model tests."
        : "Search SSC, HSC, Admission, NU, and BCS question banks on ExamDao. Find questions by keywords, topics, or subjects.";

    // OG Image
    $image = url('/images/og-home.webp'); // reuse home OG or create a search-specific OG
    $canonical = url()->current();
@endphp
@endsection

@section('content')
<div class="min-h-screen bg-secondary-100">
    <div class="max-w-7xl mx-auto bg-white shadow-2xl rounded-xl p-1 sm:p-2 md:p-4">

        {{-- Page Header --}}
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-primary-700 mb-1 sm:mb-2 md:mb-4 border-b pb-2 sm:pb-3 text-center">
            Question Bank Text Search
        </h1>

        {{-- Search Box & Filters Container --}}
        <div class="mb-6 sm:mb-8 p-4 sm:p-6 bg-primary-50 rounded-xl shadow-inner">
            <form method="GET" action="{{ route('questions.index') }}" class="flex gap-2">
                
                {{-- Go to More Filters Button --}}
                <a href="{{ route('search') }}"
                   class="bg-secondary-200 text-primary-600 border border-primary-600 px-3 sm:px-4 py-2 rounded-lg shadow-sm hover:bg-primary-50 flex items-center justify-center gap-1 min-w-10 transition duration-150 text-sm sm:text-base flex-shrink-0">
                    <x-icons.funnel/>
                    <span class="hidden sm:inline">More Filter</span>
                </a>
                
                {{-- Text Search Input --}}
                <input 
                    type="text" 
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search questions by text..."
                    class="flex-1 p-3 border border-secondary-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                >

                {{-- Search Button --}}
                <button 
                    type="submit"
                    class="bg-primary-600 text-white px-3 sm:px-4 py-2 rounded-lg shadow-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-150 flex items-center justify-center text-sm sm:text-base min-w-10 flex-shrink-0"
                >
                    <x-icons.search/>
                    <span class="hidden sm:inline ml-1">Search</span>
                </button>

            </form>
        </div>
        
        @include('partials.post-loop')
    </div>
</div>
@endsection