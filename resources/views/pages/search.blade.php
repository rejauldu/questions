@extends('layout')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto bg-white shadow-2xl rounded-xl p-4 sm:p-6 md:p-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-indigo-700 mb-6 sm:mb-8 border-b pb-2 sm:pb-3 text-center">
            Question Bank Search
        </h1>

        <!-- Filter Controls -->
        <div class="mb-6 sm:mb-8 p-4 sm:p-6 bg-indigo-50 rounded-xl shadow-inner">
            <form id="search-form" method="GET" action="{{ route('search') }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 mb-4 sm:mb-6 items-end">

                    <!-- Institution Dropdown -->
                    <div class="col-span-1">
                        <label for="institution_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Institution</label>
                        <select id="institution_id" name="institution_id"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 js-filter-trigger text-xs sm:text-sm">
                            <option value="">All Institutions</option>
                            @foreach($initialFilters['institutions'] ?? [] as $institution)
                                <option value="{{ $institution->id }}"
                                    {{ ($currentParams['institution_id'] ?? '') == $institution->id ? 'selected' : '' }}>
                                    {{ explode('/', $institution->name)[0] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subject Dropdown (AJAX) -->
                    <div class="col-span-1">
                        <label for="subject_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <select id="subject_id" name="subject_id"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 js-filter-trigger text-xs sm:text-sm">
                            <option value="">Select Institution First</option>
                        </select>
                    </div>

                    <!-- Year Dropdown -->
                    <div class="col-span-1">
                        <label for="year" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Year</label>
                        <select id="year" name="year"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 js-filter-trigger text-xs sm:text-sm">
                            <option value="">All Years</option>
                            @foreach($initialFilters['years'] ?? [] as $year)
                                <option value="{{ $year }}"
                                    {{ ($currentParams['year'] ?? '') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Class Dropdown -->
                    <div class="col-span-1">
                        <label for="class" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Class</label>
                        <select id="class" name="class"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm">
                            <option value="">All Classes</option>
                            @foreach($initialFilters['classes'] as $class)
                                <option value="{{ $class }}"
                                    {{ ($currentParams['class'] ?? '') == $class ? 'selected' : '' }}>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Dropdown -->
                    <div class="col-span-1">
                        <label for="category" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Question Type</label>
                        <select id="category" name="category"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm">
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

                <div class="w-full">
                    <button type="submit"
                        class="w-full h-8 sm:h-10 px-3 sm:px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition text-sm sm:text-base">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Section -->
        <div id="search-results-container">
            <p class="text-sm sm:text-base font-semibold text-gray-700 mb-4">
                Showing {{ $posts->count() }} of {{ $posts->total() }} Questions (10 per page)
            </p>

            @if($posts->isEmpty())
                <div class="text-center py-8 sm:py-16 bg-yellow-50 rounded-lg border border-yellow-200">
                    <p class="text-lg sm:text-xl text-yellow-700 font-medium">No questions matched your search criteria.</p>
                    <p class="text-xs sm:text-sm text-yellow-600 mt-1 sm:mt-2">Try adjusting your filters.</p>
                </div>
            @else
                <div class="space-y-6 sm:space-y-8">
                    @foreach($posts as $post)
                        <div class="border border-gray-200 p-4 sm:p-6 rounded-xl bg-white shadow-lg hover:shadow-xl transition">

                            <div class="text-xs sm:text-sm font-semibold text-yellow-700 mb-2 sm:mb-3 flex flex-wrap gap-x-2 sm:gap-x-4">
                                <h4>
                                    {{ implode(' - ', array_filter([
                                        explode('/', $post->institution->name)[0] ?? null,
                                        $post->subject->name ?? null,
                                        $post->class ?? null,
                                        $post->topic ?? null,
                                        $post->board ?? null,
                                        $post->year ?? null
                                    ])) }}
                                </h4>
                            </div>

                            <div class="flex justify-between items-start mb-2 sm:mb-4">
                                <h3 class=" ml-1 sm:ml-2 text-base sm:text-lg md:text-lg font-bold text-gray-900">
                                    {{ $post->article }}
                                </h3>

                                <button class="copy-btn flex items-center gap-1 text-gray-500 hover:text-gray-700 text-xs sm:text-sm font-medium transition duration-150 ease-in-out" data-copy="{{ $post->article }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <rect x="7" y="7" width="10" height="10" rx="2" ry="2"/>
                                        <path d="M11 7V5c0-1.105-.895-2-2-2H5c-1.105 0-2 .895-2 2v4c0 1.105.895 2 2 2h2"/>
                                    </svg>
                                    Copy
                                </button>
                            </div>

                            <div class="space-y-1 sm:space-y-2 text-gray-700 ml-1 sm:ml-2 text-xs sm:text-sm">
                                <p>ক) {!! $post->a !!}</p>
                                <p>খ) {!! $post->b !!}</p>
                                <p>গ) {!! $post->c !!}</p>
                                <p>ঘ) {!! $post->d !!}</p>
                            </div>

                            <div class="mt-3 sm:mt-4 pt-2 sm:pt-3 border-t border-gray-100">
                                <button type="button"
                                    class="toggle-answer text-xs sm:text-sm font-semibold px-3 py-1 sm:px-4 sm:py-2 rounded-full bg-indigo-100 text-indigo-700 hover:bg-indigo-200">
                                    Show Answer
                                </button>

                                <div class="answer-display mt-2 sm:mt-3 p-2 sm:p-3 bg-indigo-50 border-l-4 border-indigo-400 text-indigo-700 text-xs sm:text-sm rounded-lg hidden">
                                    <p class="font-bold">Correct Answer: {{ $post->answer }}</p>
                                </div>
                            </div>
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

    document.addEventListener("DOMContentLoaded", () => {
        const institution = document.getElementById("institution_id");
        const subjectDropdown = document.getElementById("subject_id");

        function loadSubjects(id) {
            subjectDropdown.innerHTML = `<option>Loading...</option>`;

            fetch(`${SUBJECTS_API_URL}?institution_id=${id}`)
                .then(res => res.json())
                .then(data => {
                    subjectDropdown.innerHTML = `<option value="">All Subjects</option>`;
                    data.forEach(sub => {
                        subjectDropdown.innerHTML += `
                            <option value="${sub.id}" ${CURRENT_SUBJECT === sub.id ? 'selected' : ''}>
                                ${sub.name}
                            </option>`;
                    });
                })
                .catch(() => {
                    subjectDropdown.innerHTML = `<option>Error loading subjects</option>`;
                });
        }

        if (CURRENT_INSTITUTION_ID) {
            loadSubjects(CURRENT_INSTITUTION_ID);
        }

        institution.addEventListener("change", function () {
            if (this.value) loadSubjects(this.value);
            else subjectDropdown.innerHTML = `<option value="">Select Institution First</option>`;
        });

        document.querySelectorAll(".toggle-answer").forEach(btn => {
            btn.addEventListener("click", function () {
                const box = this.nextElementSibling;
                box.classList.toggle("hidden");
                this.textContent = box.classList.contains("hidden") ? "Show Answer" : "Hide Answer";
            });
        });
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".copy-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const text = this.getAttribute("data-copy");
            if (!text) return;

            // Use Clipboard API if available
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => showFeedback(this));
            } else {
                // Fallback for insecure HTTP / older browsers
                const textarea = document.createElement("textarea");
                textarea.value = text;
                textarea.style.position = "fixed"; // prevent scrolling
                textarea.style.opacity = "0";
                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();
                try {
                    document.execCommand('copy');
                    showFeedback(this);
                } catch (err) {
                    console.error("Fallback: Copy failed", err);
                }
                document.body.removeChild(textarea);
            }
        });
    });

    function showFeedback(button) {
        const originalContent = button.innerHTML;
        button.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Copied
        `;
        setTimeout(() => { button.innerHTML = originalContent; }, 1000);
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        MathJax.typesetPromise();
    });
    MathJax.typesetPromise();
</script>
@endpush