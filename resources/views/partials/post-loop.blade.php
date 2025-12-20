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
                            {{-- Layout with Image --}}
                            <div class="flex flex-row items-center space-x-4 w-full">
                                
                                {{-- Fixed Square Thumbnail --}}
                                <div class="flex-shrink-0">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-lg border border-secondary-200 overflow-hidden shadow-sm relative">
                                        <img src="{{ asset($post->url) }}" 
                                            alt="Question Image" 
                                            class="absolute inset-0 w-full h-full object-cover object-center" />
                                    </div>
                                </div>

                                {{-- Article Text (Max 4 Lines) --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base sm:text-lg font-bold text-secondary-900 leading-snug line-clamp-3">
                                        {!! $post->article !!}
                                    </h3>
                                </div>
                            </div>
                        @else
                            {{-- Text Only Layout --}}
                            <div class="mb-2 sm:mb-4">
                                <h3 class="ml-1 sm:ml-2 text-base sm:text-lg md:text-lg font-bold text-secondary-900 line-clamp-4">
                                    {!! $post->article !!}
                                </h3>
                            </div>

                            {{-- Options --}}
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