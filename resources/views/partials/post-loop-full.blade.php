{{-- resources/views/partials/post-loop.blade.php --}}
<div class="space-y-1 md:space-y-6">
    @if($posts->count() > 0)
        <div class="space-y-2 md:space-y-4">
            @foreach($posts as $post)
                <div class="group relative bg-white border border-slate-200 rounded-xl md:rounded-2xl p-2.5 md:p-4 mt-1 md:mt-4 hover:border-emerald-500 hover:shadow-lg transition-all duration-300">
                    
                    {{-- Top Meta Row --}}
                    <div class="flex justify-between items-center mb-1.5 md:mb-3">
                        <div class="flex items-center gap-1.5 md:gap-2">
                            {{-- ID Badge --}}
                            <span class="bg-secondary-200 text-secondary-600 px-1 md:px-1.5 py-0.5 rounded text-[9px] md:text-xs font-bold">ID#{{ $post->id }}</span>
                            <span class="px-1.5 md:px-2 py-0.5 rounded-full text-[9px] md:text-xs bg-emerald-50 text-emerald-700 border border-emerald-100 font-bold">
                                {{ question_meta_text($post) }}
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
                        </div>
                        @php
                            $copy_data = $post->image1 
                                ? strip_tags($post->article) 
                                : strip_tags($post->article) . "\n\n" 
                                    . "ক) " . strip_tags($post->a) . "\n" 
                                    . "খ) " . strip_tags($post->b) . "\n" 
                                    . "গ) " . strip_tags($post->c) . "\n" 
                                    . "ঘ) " . strip_tags($post->d);
                        @endphp
                        <button class="copy-btn text-slate-300 hover:text-emerald-600 transition-colors" 
                                data-copy="{{ $copy_data }}">
                            <x-icons.copy class="w-3.5 h-3.5 md:w-4 md:h-4"/>
                        </button>
                    </div>

                    {{-- Question Content --}}
                    <a href="{{ route('questions.show', ['question' => $post->id, 'slug' => url_slug($post->article, $subject->name ?? 'question')]) }}" class="block">
                        <div class="flex gap-3 md:gap-4">
                            @if($post->image1)
                                <div class="shrink-0 w-14 h-14 md:w-20 md:h-20 rounded-lg overflow-hidden border border-slate-100 shadow-inner">
                                    <img src="{{ asset($post->image1) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h3 class="text-xs md:text-base text-slate-700 font-medium leading-snug md:leading-relaxed mb-2 md:mb-3 group-hover:text-emerald-700 transition-colors">
                                    {!! nl2br($post->article) !!}
                                </h3>

                                {{-- Dynamic Option Grid --}}
                                @php
                                    $options = array_filter(['a' => $post->a, 'b' => $post->b, 'c' => $post->c, 'd' => $post->d]);
                                    $totalLength = strlen(strip_tags(implode('', $options)));
                                    $isShort = $totalLength < 100;
                                @endphp

                                @if(count($options) > 0)
                                    <div class="grid {{ $isShort ? 'grid-cols-2' : 'grid-cols-1' }} gap-1.5 md:gap-2">
                                        @foreach(['a'=>'ক','b'=>'খ','c'=>'গ','d'=>'ঘ'] as $key => $label)
                                            @if(!empty(trim(strip_tags($post->$key))))
                                                <div class="flex items-start gap-1.5 md:gap-2 px-2 md:px-3 py-1.5 md:py-2 rounded-lg md:rounded-xl bg-slate-50 border border-slate-100 text-[11px] md:text-[13px] text-slate-600 group-hover:bg-emerald-50/50 transition-colors">
                                                    <span class="flex-shrink-0 w-4 h-4 md:w-5 md:h-5 rounded-full bg-white border border-slate-200 flex items-center justify-center font-bold text-emerald-600 text-[9px] md:text-[10px] mt-0.5">
                                                        {{ $label }}
                                                    </span>
                                                    <div class="leading-tight md:leading-snug">{!! strip_tags($post->$key) !!}</div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white py-12 md:py-24 rounded-2xl md:rounded-[40px] border border-dashed border-slate-200 text-center">
            <div class="mb-2 md:mb-4 flex justify-center">
                <div class="p-3 md:p-4 bg-slate-50 rounded-full">
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"></path></svg>
                </div>
            </div>
            <p class="text-slate-400 text-xs md:text-sm font-bold tracking-tight">No results found for this filter.</p>
        </div>
    @endif
</div>