{{-- Results Container --}}
<div id="search-results-container">
    <div class="flex items-center justify-between mb-6 px-2">
        <p class="text-xs sm:text-sm font-medium text-slate-500 italic">
            Showing <span class="text-slate-900 font-bold">{{ $posts->count() }}</span> of {{ $posts->total() }} results
        </p>
    </div>

    @if($posts->isEmpty())
        <div class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
            <div class="mb-3 text-slate-400">
                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-slate-600 font-medium">No questions found matching your criteria.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($posts as $post)
                <div class="group relative bg-white border border-slate-200 p-4 sm:p-5 rounded-2xl hover:border-primary-400 hover:shadow-md transition-all duration-300">
                    
                    {{-- Top Bar: Meta & Actions --}}
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-bold bg-primary-50 text-primary-700 border border-primary-100">
                                {{ $q_meta = question_meta_text($post) }}
                            </span>
                            {{-- New Category Badge --}}
                            @if($post->category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] sm:text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200 tracking-wider">
                                    <svg class="w-2.5 h-2.5 mr-1 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                    </svg>
                                    {{ ($post->institution_id == 4 && $post->category == 'MCQ') ? 'Preli' : $post->category }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                            {{-- Bookmark Toggle Removed --}}

                            @php
                                $copy_data = $post->image1 
                                    ? strip_tags($post->article) 
                                    : strip_tags($post->article) . "\n\n" 
                                        . "ক) " . strip_tags($post->a) . "\n" 
                                        . "খ) " . strip_tags($post->b) . "\n" 
                                        . "গ) " . strip_tags($post->c) . "\n" 
                                        . "ঘ) " . strip_tags($post->d);
                            @endphp

                            <button class="copy-btn flex items-center gap-1.5 text-slate-400 hover:text-primary-600 transition-colors duration-200" 
                                    data-copy="{{ $copy_data }}" 
                                    title="Copy Question">
                                <x-icons.copy class="w-4 h-4"/>
                                <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider">Copy</span>
                            </button>
                        </div>
                    </div>

                    {{-- Question Content --}}
                    <a href="{{ route('questions.show', ['question' => $post->id, 'slug' => url_slug($post->article, $q_meta)]) }}"
                        class="block">

                        @if ($post->image1)
                            <div class="flex gap-4 items-start">
                                {{-- Thumbnail --}}
                                <div class="flex-shrink-0">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-slate-100 rounded-xl overflow-hidden border border-slate-100 relative group-hover:border-primary-200 transition-all">
                                        <img src="{{ asset($post->image1) }}" 
                                             alt="Question Visual" 
                                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                    </div>
                                </div>

                                {{-- Text --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base text-slate-800 leading-relaxed line-clamp-3 group-hover:text-primary-700 transition-colors">
                                        {!! nl2br($post->article ?? "") !!}
                                    </h3>
                                </div>
                            </div>
                        @else
                            {{-- Text Only --}}
                            <div class="relative">
                                <h3 class="text-base text-slate-800 leading-relaxed line-clamp-4 text-justify group-hover:text-primary-700 transition-colors">
                                    {!! nl2br($post->article ?? "") !!}
                                </h3>
                            </div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Professional Pagination --}}
        <div class="mt-10 mb-6 px-4">
            {{ $posts->links() }}
        </div>
    @endif
</div>