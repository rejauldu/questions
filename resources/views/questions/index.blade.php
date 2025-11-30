@extends('layout')

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
        
        {{-- Results Count --}}
        <p class="text-sm sm:text-base font-semibold text-secondary-700 mb-4">
            Showing {{ $posts->count() }} of {{ $posts->total() }} Questions (10 per page)
        </p>
        
        {{-- Questions Container --}}
        @if ($posts->isEmpty())
            <div class="text-center py-8 sm:py-16 bg-warning-50 rounded-lg border border-warning-200">
                <p class="text-lg sm:text-xl text-warning-700 font-medium">No questions matched your search criteria.</p>
                <p class="text-xs sm:text-sm text-warning-600 mt-1 sm:mt-2">Try adjusting your search terms.</p>
            </div>
        @else
            <div id="questions-container" class="space-y-6 sm:space-y-8"> 
                @foreach ($posts as $post)
                    <div class="border border-secondary-200 p-4 sm:p-6 rounded-xl bg-white shadow-lg hover:shadow-xl transition">
                        
                        {{-- Top Bar: Meta and Copy Button --}}
                        <div class="flex justify-between items-start mb-2 sm:mb-3">
                            {{-- Meta --}}
                            <div class="text-xs sm:text-sm font-semibold text-warning-700 flex flex-wrap gap-x-2 sm:gap-x-4 justify-start">
                                <h4>{{ $q_meta = question_meta_text($post) }}</h4>
                            </div>
                            
                            {{-- Copy Button --}}
                            @php
                                $copy_data = $post->url
                                    ? strip_tags($post->article)
                                    : strip_tags($post->article) . "\n\nক) " . strip_tags($post->a) . "\nখ) " . strip_tags($post->b) . "\nগ) " . strip_tags($post->c) . "\nঘ) " . strip_tags($post->d);
                            @endphp
                            <button class="copy-btn flex items-center gap-1 text-secondary-500 hover:text-secondary-700 text-xs sm:text-sm font-medium transition duration-150 ease-in-out flex-shrink-0 whitespace-nowrap" data-copy="{{ $copy_data }}">
                                <x-icons.copy />
                                Copy
                            </button>
                        </div>
                        
                        {{-- Main clickable question area --}}
                        <a href="{{ route('questions.show', ['question' => $post->id, 'slug' => url_slug($post->article, $q_meta)]) }}" 
                           class="block group question-card-link pt-2 border-t border-secondary-100">
                            
                            @if ($post->url)
                                <div class="mb-2 sm:mb-2">
                                    <h3 class="ml-1 sm:ml-2 text-base sm:text-lg md:text-lg font-bold text-secondary-900 mb-2 line-clamp-2">
                                        {!! $post->article !!}
                                    </h3>
                                    <div class="ml-1 sm:ml-2">
                                        <img src="{{ asset('storage/' . $post->url) }}" 
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

            {{-- Pagination --}}
            <div class="mt-6 sm:mt-8 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif

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