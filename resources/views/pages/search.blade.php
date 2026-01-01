@extends('layout')
@section('seo')
@php
    // Collect all active filters in order
    $activeFilters = [];

    if (!empty($currentParams['institution_name'])) {
        $activeFilters[] = firstpart($currentParams['institution_name']);
    }
    if (!empty($currentParams['subject_name'])) {
        $activeFilters[] = $currentParams['subject_name'];
    }
    if (!empty($currentParams['class'])) {
        $activeFilters[] = ordinal_suffix($currentParams['class']) . ' year';
    }
    if (!empty($currentParams['year'])) {
        $activeFilters[] = $currentParams['year'];
    }
    if (!empty($currentParams['topic'])) {
        $activeFilters[] = $currentParams['topic'];
    }
    if (!empty($currentParams['chapter'])) {
        $activeFilters[] = $currentParams['chapter'];
    }
    if (!empty($currentParams['section'])) {
        $activeFilters[] = $currentParams['section'];
    }
    if (!empty($currentParams['sub_section'])) {
        $activeFilters[] = $currentParams['sub_section'];
    }
    if (!empty($currentParams['category'])) {
        $activeFilters[] = ucfirst($currentParams['category']);
    }

    // SEO Keyword: join filters with comma for better indexing
    $searchKeyword = !empty($activeFilters) ? implode(" ", $activeFilters) : "Filtered Questions";

    // SEO Title
    $title = "$searchKeyword - Questions & Solutions | ExamDao";

    // SEO Description: include all filters
    $description = "Explore questions on ExamDao";
    if (!empty($activeFilters)) {
        $description .= " filtered by " . implode(" ", $activeFilters);
    }
    $description .= ". Access chapter-wise questions, past papers, model tests, and verified solutions for SSC, HSC, Admission, NU & BCS exams.";

    // OG Image
    $image = url('/images/og-home.webp');

    // Canonical URL
    $canonical = url()->current();
@endphp
@endsection

@section('content')
<div class="min-h-screen bg-secondary-100">
    <div class="max-w-7xl mx-auto bg-white shadow-2xl rounded-xl p-1 sm:p-2 md:p-4">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-primary-700 mb-1 sm:mb-2 md:mb-4 border-b pb-2 sm:pb-3 text-center">
            Question Bank Filter
        </h1>

        {{-- Filter Section --}}
        <div class="mb-6 sm:mb-8 p-4 sm:p-6 bg-primary-50 rounded-xl shadow-inner">
            <form id="search-form" method="GET" action="{{ route('search') }}">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4 sm:mb-6 items-end">

                    {{-- Institution --}}
                    <div class="col-span-1">
                        <label for="institution_id" class="block text-xs sm:text-sm font-medium text-secondary-700 mb-1">
                            Institution
                        </label>
                        <select id="institution_id" name="institution_id"
                            class="w-full border-secondary-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 js-filter-trigger text-xs sm:text-sm">
                            <option value="">All Institutions</option>
                            @foreach($initialFilters['institutions'] ?? [] as $institution)
                                <option value="{{ $institution->id }}"
                                    {{ ($currentParams['institution_id'] ?? '') == $institution->id ? 'selected' : '' }}>
                                    {{ explode('/', $institution->name)[0] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Subject --}}
                    <div class="col-span-1">
                        <label for="subject_id" class="block text-xs sm:text-sm font-medium text-secondary-700 mb-1">
                            Subject
                        </label>
                        <select id="subject_id" name="subject_id"
                            class="w-full border-secondary-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 js-filter-trigger text-xs sm:text-sm">
                            <option value="">Select Institution First</option>
                        </select>
                    </div>

                    {{-- Board --}}
                    <div class="col-span-1">
                        <label for="board_id" class="block text-xs sm:text-sm font-medium text-secondary-700 mb-1">
                            Board
                        </label>
                        <select id="board_id" name="board_id"
                            class="w-full border-secondary-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 js-filter-trigger text-xs sm:text-sm">
                            <option value="">All Boards</option>
                            @foreach($initialFilters['boards'] ?? [] as $board)
                                <option value="{{ $board->id }}"
                                    {{ ($currentParams['board_id'] ?? '') == $board->id ? 'selected' : '' }}>
                                    {{ $board->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Year --}}
                    <div class="col-span-1">
                        <label for="year" class="block text-xs sm:text-sm font-medium text-secondary-700 mb-1">
                            Year
                        </label>
                        <select id="year" name="year"
                            class="w-full border-secondary-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 js-filter-trigger text-xs sm:text-sm">
                            <option value="">All Years</option>
                            @foreach($initialFilters['years'] ?? [] as $year)
                                <option value="{{ $year }}"
                                    {{ ($currentParams['year'] ?? '') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Class --}}
                    <div class="col-span-1">
                        <label for="class" class="block text-xs sm:text-sm font-medium text-secondary-700 mb-1">
                            Class
                        </label>
                        <select id="class" name="class"
                            class="w-full border-secondary-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-xs sm:text-sm">
                            <option value="">All Classes</option>
                            @foreach($initialFilters['classes'] ?? [] as $class)
                                <option value="{{ $class }}"
                                    {{ ($currentParams['class'] ?? '') == $class ? 'selected' : '' }}>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- Button Group --}}
                <div class="w-full flex gap-2">
                    <button type="submit"
                        class="flex-1 h-8 sm:h-10 px-3 sm:px-4 py-2 bg-primary-600 text-white font-semibold rounded-lg shadow-md hover:bg-primary-700 transition text-sm sm:text-base">
                        Search
                    </button>

                    <a href="{{ route('questions.index') }}"
                        class="h-8 sm:h-10 px-3 sm:px-4 py-2 text-primary-600 border border-primary-600 font-semibold rounded-lg shadow-sm hover:bg-primary-50 transition text-sm sm:text-base flex items-center justify-center gap-1 flex-shrink-0">
                        <x-icons.writing />
                        Text Search
                    </a>
                </div>
            </form>
        </div>

        @include('partials.post-loop')
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    if (window.MathJax) {
        MathJax.typesetPromise();
    }
});
</script>
@endpush