@extends('layout')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto bg-white shadow-2xl rounded-xl p-4">
        <h1 class="text-4xl font-extrabold text-indigo-700 mb-8 border-b pb-3 text-center">
            Question Bank Search
        </h1>

        <!-- Filter Controls and Search Box -->
        <div class="mb-8 p-6 bg-indigo-50 rounded-xl shadow-inner">
            <form id="search-form" method="GET" action="{{ route('search') }}">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 items-end">
                    
                    <!-- Institution Dropdown -->
                    <div class="col-span-1">
                        <label for="institution_id" class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
                        <select id="institution_id" name="institution_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 js-filter-trigger">
                            <option value="">All Institutions</option>
                            @foreach($initialFilters['institutions'] ?? [] as $institution)
                                <option value="{{ $institution->id }}" {{ ($currentParams['institution_id'] ?? '') == $institution->id ? 'selected' : '' }}>
                                    {{ $institution->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subject Dropdown (AJAX loaded) -->
                    <div class="col-span-1">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <select id="subject" name="subject"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 js-filter-trigger">
                            <option value="" selected>Select Institution First</option>
                        </select>
                    </div>

                    <!-- Topic Dropdown -->
                    <div class="col-span-1">
                        <label for="topic" class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
                        <select id="topic" name="topic"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 js-filter-trigger">
                            <option value="">All Topics</option>
                            @foreach($initialFilters['topics'] ?? [] as $topic)
                                <option value="{{ $topic }}" {{ ($currentParams['topic'] ?? '') == $topic ? 'selected' : '' }}>
                                    {{ $topic }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Dropdown -->
                    <div class="col-span-1">
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <select id="year" name="year"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 js-filter-trigger">
                            <option value="">All Years</option>
                            @foreach($initialFilters['years'] ?? [] as $year)
                                <option value="{{ $year }}" {{ ($currentParams['year'] ?? '') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Button -->
                    <div class="col-span-1">
                        <button type="submit" id="search-button"
                                class="w-full h-10 px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Search
                        </button>
                    </div>
                </div>

                <!-- Search Term Input -->
                <div class="w-full">
                    <label for="search_term" class="block text-sm font-medium text-gray-700 mb-1">Search Question/Options</label>
                    <input id="search_term" type="text" name="search_term"
                           value="{{ $currentParams['search_term'] ?? '' }}"
                           placeholder="Enter keywords, phrases, or option text..."
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
            </form>
        </div>

        <!-- Results Section -->
        <div id="search-results-container">
            <p class="text-lg font-semibold text-gray-700 mb-4">
                Showing {{ $posts->count() }} of {{ $posts->total() }} Questions (10 per page)
            </p>

            @if($posts->isEmpty())
                <div class="text-center py-16 bg-yellow-50 rounded-lg border border-yellow-200">
                    <p class="text-xl text-yellow-700 font-medium">No questions matched your search criteria.</p>
                    <p class="text-sm text-yellow-600 mt-2">Try adjusting your filters or search term.</p>
                </div>
            @else
                <div class="space-y-8">
                    @foreach($posts as $post)
                        <div class="border border-gray-200 p-6 rounded-xl bg-white shadow-lg hover:shadow-xl transition duration-300">

                            <!-- Metadata -->
                            <div class="text-xs font-semibold text-gray-500 mb-3 flex flex-wrap gap-x-4">
                                <span>{{ $post->subject }} / {{ $post->topic }}</span>
                                <span>Class: {{ $post->class ?? 'N/A' }}</span>
                                <span>Year: {{ $post->year }}</span>
                                <span>Board: {{ $post->board ?? 'N/A' }}</span>
                            </div>

                            <!-- Question -->
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                Q{{ $posts->firstItem() + $loop->index }}: {{ $post->article }}
                            </h3>

                            <!-- Options -->
                            <div class="space-y-2 text-gray-700 ml-4">
                                <p>A: {{ $post->a }}</p>
                                <p>B: {{ $post->b }}</p>
                                <p>C: {{ $post->c }}</p>
                                <p>D: {{ $post->d }}</p>
                            </div>

                            <!-- Answer Toggle -->
                            <div class="mt-4 pt-3 border-t border-gray-100">
                                <button type="button" class="toggle-answer text-sm font-semibold px-4 py-2 rounded-full bg-green-100 text-green-700 hover:bg-green-200 transition duration-150">
                                    Show Answer
                                </button>

                                <div class="answer-display mt-3 p-3 bg-green-50 border-l-4 border-green-400 text-green-800 rounded-lg hidden">
                                    <p class="font-bold">Correct Answer: {{ $post->answer }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
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
    const CURRENT_SUBJECT = "{{ $currentParams['subject'] ?? '' }}";
    const CURRENT_INSTITUTION_ID = "{{ $currentParams['institution_id'] ?? '' }}";
</script>
@endpush