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

                    <div class="col-span-1">
                        <label for="institution_id" class="block text-xs sm:text-sm font-medium text-secondary-700 mb-1">Institution</label>
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

                    <div class="col-span-1">
                        <label for="subject_id" class="block text-xs sm:text-sm font-medium text-secondary-700 mb-1">Subject</label>
                        <select id="subject_id" name="subject_id"
                            class="w-full border-secondary-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 js-filter-trigger text-xs sm:text-sm">
                            <option value="">Select Institution First</option>
                        </select>
                    </div>

                    <div class="col-span-1">
                        <label for="year" class="block text-xs sm:text-sm font-medium text-secondary-700 mb-1">Year</label>
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

                    <div class="col-span-1">
                        <label for="class" class="block text-xs sm:text-sm font-medium text-secondary-700 mb-1">Class</label>
                        <select id="class" name="class"
                            class="w-full border-secondary-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-xs sm:text-sm">
                            <option value="">All Classes</option>
                            @foreach($initialFilters['classes'] as $class)
                                <option value="{{ $class }}"
                                    {{ ($currentParams['class'] ?? '') == $class ? 'selected' : '' }}>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-1">
                        <label for="category" class="block text-xs sm:text-sm font-medium text-secondary-700 mb-1">Question Type</label>
                        <select id="category" name="category"
                            class="w-full border-secondary-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-xs sm:text-sm">
                            <option value="">All Categories</option>
                            @foreach($initialFilters['categories'] ?? [] as $category)
                                <option value="{{ $category }}"
                                    {{ ($currentParams['category'] ?? '') == $category ? 'selected' : '' }}>
                                    {{ $category }}
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
                        <x-icons.writing/>
                        Text Search
                    </a>
                </div>
            </form>
        </div>

        {{-- Results Container --}}
        <div id="search-results-container">
            <p class="text-sm sm:text-base font-semibold text-secondary-700 mb-4">
                Showing {{ $posts->count() }} of {{ $posts->total() }} Questions (10 per page)
            </p>

            @if($posts->isEmpty())
                <div class="text-center py-8 sm:py-16 bg-warning-50 rounded-lg border border-warning-200">
                    <p class="text-lg sm:text-xl text-warning-700 font-medium">No questions matched your search criteria.</p>
                    <p class="text-xs sm:text-sm text-warning-600 mt-1 sm:mt-2">Try adjusting your filters.</p>
                </div>
            @else
                <div class="space-y-6 sm:space-y-8">
                    @foreach($posts as $post)
                        <div class="border border-secondary-200 p-4 sm:p-6 rounded-xl bg-white shadow-lg hover:shadow-xl transition">
                            
                            {{-- Top Bar --}}
                            <div class="flex justify-between items-start mb-2 sm:mb-3">
                                <div class="text-xs sm:text-sm font-semibold text-warning-700 flex flex-wrap gap-x-2 sm:gap-x-4 justify-start">
                                    <h4>{{ $q_meta = question_meta_text($post) }}</h4>
                                </div>

                                @php
                                    $copy_data = $post->url 
                                        ? strip_tags($post->article) 
                                        : strip_tags($post->article) . "\n\n" 
                                            . "ক) " . strip_tags($post->a) . "\n" 
                                            . "খ) " . strip_tags($post->b) . "\n" 
                                            . "গ) " . strip_tags($post->c) . "\n" 
                                            . "ঘ) " . strip_tags($post->d);
                                @endphp

                                <button class="copy-btn flex items-center gap-1 text-secondary-500 hover:text-secondary-700 text-xs sm:text-sm font-medium transition duration-150 ease-in-out flex-shrink-0 whitespace-nowrap" data-copy="{{ $copy_data }}">
                                    <x-icons.copy/>
                                    Copy
                                </button>
                            </div>

                            {{-- Question Content --}}
                            <a href="{{ route('questions.show', ['question' => $post->id, 'slug' => url_slug($post->article, $q_meta)]) }}"
                                class="block group question-card-link pt-2 border-t border-secondary-100">

                                @if ($post->url)
                                    <div class="mb-2 sm:mb-2">
                                        <h3 class="ml-1 sm:ml-2 text-base sm:text-lg md:text-lg font-bold text-secondary-900 mb-2 line-clamp-2">
                                            {!! $post->article !!}
                                        </h3>

                                        <div class="ml-1 sm:ml-2">
                                            <img src="{{ asset($post->url) }}" 
                                                alt="Question Image" 
                                                class="h-20 w-20 object-contain rounded-lg shadow-inner border border-secondary-200"
                                                style="height: 80px; width: 80px;" />
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-2 sm:mb-4">
                                        <h3 class="ml-1 sm:ml-2 text-base sm:text-lg md:text-lg font-bold text-secondary-900 line-clamp-2">
                                            {!! $post->article !!}
                                        </h3>
                                    </div>

                                    <div class="space-y-1 sm:space-y-2 text-secondary-700 ml-1 sm:ml-2 text-xs sm:text-sm">
                                        <p class="line-clamp-1">ক) {!! $post->a !!}</p>
                                        <p class="line-clamp-1">খ) {!! $post->b !!}</p>
                                        <p class="line-clamp-1">গ) {!! $post->c !!}</p>
                                        <p class="line-clamp-1">ঘ) {!! $post->d !!}</p>
                                    </div>
                                @endif

                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 sm:mt-8 flex justify-center">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const SUBJECTS_API_URL = "{{ route('api.posts.subjects-by-institution') }}";
    const CURRENT_SUBJECT = @json($currentParams['subject_id'] ?? '');
    const CURRENT_INSTITUTION_ID = @json($currentParams['institution_id'] ?? '');
</script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    if (window.MathJax) {
        MathJax.typesetPromise();
    }
});
</script>
@endpush