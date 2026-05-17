{{-- Results Container --}}
<div id="search-results-container">

    @if($posts->isEmpty())
        <div class="text-center py-10 md:py-16 bg-slate-50 rounded-xl md:rounded-2xl border border-dashed border-slate-300">
            <div class="mb-2 md:mb-3 text-slate-400">
                <svg class="w-10 h-10 md:w-12 md:h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-slate-600 text-xs md:text-sm font-medium">No questions found matching your criteria.</p>
        </div>
    @else
        <div class="space-y-1 md:space-y-4">
            @foreach($posts as $post)
                <div class="group relative bg-white border border-slate-200 p-3 md:p-5 rounded-xl md:rounded-2xl hover:border-primary-400 hover:shadow-md transition-all duration-300">
                    
                    {{-- Top Bar: Meta & Actions --}}
                    <div class="flex justify-between items-center mb-2 md:mb-4">
                        <div class="flex flex-wrap gap-1 md:gap-2">
                            {{-- ID Badge --}}
                            <span class="bg-secondary-200 text-secondary-600 px-1 py-0.5 rounded text-[9px] md:text-xs font-bold">ID#{{ $post->id }}</span>

                            <span class="inline-flex items-center px-1 md:px-2.5 py-0.5 rounded text-[9px] md:text-xs font-bold bg-primary-50 text-primary-700 border border-primary-100">
                                {{ $q_meta = question_meta_text($post) }}
                            </span>

                            @if($post->importance > 0)
                                <div class="flex items-center gap-0.5 ml-0.5" title="Importance: {{ $post->importance }}/5">
                                    @for ($i = 1; $i <= 3; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                             viewBox="0 0 24 24" 
                                             fill="currentColor" 
                                             class="w-2.5 h-2.5 md:w-3 md:h-3 {{ $i <= $post->importance ? 'text-amber-400' : 'text-slate-200' }}">
                                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                        </svg>
                                    @endfor
                                </div>
                            @endif

                            @if($post->category)
                                <span class="inline-flex items-center px-1 md:px-2.5 py-0.5 rounded-md text-[9px] md:text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200 tracking-wider">
                                    
                                    {{ ($post->institution_id == 4 && $post->category == 'MCQ') ? 'Preli' : $post->category }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 md:gap-3">
                            @php
                                $copy_data = $post->image1 
                                    ? strip_tags($post->article) 
                                    : strip_tags($post->article) . "\n\n" 
                                        . "ক) " . strip_tags($post->a) . "\n" 
                                        . "খ) " . strip_tags($post->b) . "\n" 
                                        . "গ) " . strip_tags($post->c) . "\n" 
                                        . "ঘ) " . strip_tags($post->d);
                            @endphp

                            <button class="copy-btn flex items-center text-slate-400 hover:text-primary-600 transition-colors duration-200" 
                                    data-copy="{{ $copy_data }}" 
                                    title="Copy Question">
                                <x-icons.copy class="w-3.5 h-3.5 md:w-4 md:h-4"/>
                                <span class="text-[9px] md:text-xs font-semibold uppercase tracking-wider">Copy</span>
                            </button>
                        </div>
                    </div>

                    {{-- Question Content --}}
                    <a href="{{ route('questions.show', ['question' => $post->id, 'slug' => url_slug($post->article, $q_meta)]) }}"
                        class="block">

                        @if ($post->image1)
                            <div class="flex gap-3 md:gap-4 items-start">
                                {{-- Thumbnail --}}
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 md:w-24 md:h-24 bg-slate-100 rounded-lg md:rounded-xl overflow-hidden border border-slate-100 relative group-hover:border-primary-200 transition-all">
                                        <img src="{{ asset($post->image1) }}" 
                                             alt="Question Visual" 
                                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                    </div>
                                </div>

                                {{-- Text --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm md:text-base text-slate-800 leading-snug md:leading-relaxed line-clamp-3 group-hover:text-primary-700 transition-colors">
                                        {!! nl2br($post->article ?? "") !!}
                                    </h3>
                                </div>
                            </div>
                        @else
                            {{-- Text Only --}}
                            <div class="relative">
                                <h3 class="text-sm md:text-base text-slate-800 leading-snug md:leading-relaxed line-clamp-4 text-justify group-hover:text-primary-700 transition-colors">
                                    {!! nl2br($post->article ?? "") !!}
                                </h3>
                            </div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Professional Pagination --}}
        <div class="mt-6 md:mt-10 mb-4 md:mb-6 px-2 md:px-4">
            {{ $posts->links() }}
        </div>
    @endif
</div>