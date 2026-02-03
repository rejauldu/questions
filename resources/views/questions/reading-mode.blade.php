@extends('layout-simple')

@section('content')
<div class="min-h-screen bg-white text-[#334155] pb-32 font-sans antialiased">
    {{-- Top Slim Header --}}
    <header class="py-3 px-4 border-b border-slate-100 flex justify-between items-center max-w-3xl mx-auto sticky top-0 bg-white/90 backdrop-blur-sm z-50">
        <a href="{{ url('/') }}" class="text-slate-500 hover:text-primary-600 transition flex items-center gap-1 text-xs font-semibold">
            <x-icons.left-arrow class="w-3.5 h-3.5" /> HOME
        </a>
        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 flex items-center gap-2">
            @if(isset($isRead) && $isRead)
                <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
            @endif
            {{ !empty($q->chapter) && $q->chapter !== 'None' ? $q->topic_name : 'General Questions' }}
        </span>
    </header>

    <main class="max-w-2xl mx-auto pt-10 px-6">
        <article class="prose prose-slate max-w-none">
            {{-- Question Text --}}
            <div class="text-base sm:text-lg font-medium leading-relaxed text-slate-800 mb-2">
                {!! smart_nl2br($q->article) !!}
            </div>
            
            {{-- Ghost Metadata --}}
            <div class="flex gap-2 mb-8">
                @if($q->board?->name) <span class="text-[10px] text-slate-300 font-bold uppercase tracking-tighter">{{ $q->board->name }}</span> @endif
                @if($q->year) <span class="text-[10px] text-slate-300 font-bold uppercase tracking-tighter">• {{ $q->year }}</span> @endif
            </div>

            @php 
                $category = strtoupper($q->category);
            @endphp

            {{-- MCQ vs CQ/Writing Display Logic --}}
            @if($category === 'MCQ')
                {{-- Interactive MCQ Mode --}}
                <div id="mcq-wrapper" data-correct="{{ trim($q->ans) }}" class="grid grid-cols-1 gap-3 my-8">
                    @foreach(['a'=>'ক','b'=>'খ','c'=>'গ','d'=>'ঘ'] as $key => $label)
                        @if(isset($q->$key))
                        <button onclick="checkAnswer(this, '{{ $label }}')" 
                                class="mcq-option flex items-center justify-between p-4 rounded-xl border-2 border-slate-100 bg-white hover:border-slate-300 transition-all duration-200 text-left w-full group relative">
                            <div class="flex items-start gap-3">
                                <span class="option-label flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg bg-slate-50 text-slate-500 font-bold text-xs group-hover:bg-slate-100 transition-colors">
                                    {{ $label }}
                                </span>
                                <div class="option-text text-sm text-slate-700 pt-0.5">{!! $q->$key !!}</div>
                            </div>
                            <div class="status-icon hidden flex-shrink-0 ml-2"></div>
                        </button>
                        @endif
                    @endforeach
                </div>
            @else
                {{-- Standard Reading Mode for CQ & Writing --}}
                <div class="space-y-3 my-4">
                    @foreach(['a'=>'ক','b'=>'খ','c'=>'গ','d'=>'ঘ'] as $key => $label)
                        @if(isset($q->$key))
                        <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100">
                            <div class="text-sm sm:text-base text-slate-700 leading-relaxed prose-sm">
                               {{ $label }}) {!! $q->$key !!}
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- Question Images --}}
            <div class="space-y-6 my-8">
                @foreach(['image1', 'image2', 'image3', 'image4'] as $img)
                    @if($q->$img)
                        <img src="{{ asset($q->$img) }}" class="w-full h-auto rounded-xl border border-slate-200 shadow-sm" loading="lazy" />
                    @endif
                @endforeach
            </div>
        </article>
        {{-- Navigation Bar --}}
        <div class="flex items-center justify-between gap-4 my-4">
            <a href="{{ $prevUrl }}" class="flex-1 flex items-center justify-center gap-2 py-4 rounded-2xl bg-white border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-all shadow-sm">
                <x-icons.left-arrow class="w-3.5 h-3.5" /> PREVIOUS
            </a>
            {{-- Added id="next-question-btn" for JS targeting --}}
            <a href="{{ $nextUrl }}" id="next-question-btn" class="flex-1 flex items-center justify-center gap-2 py-4 rounded-2xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-700 transition-all shadow-lg active:scale-95">
                NEXT QUESTION <x-icons.down-arrow class="w-3.5 h-3.5 -rotate-90" />
            </a>
        </div>

        {{-- Explanation Section --}}
        @if($q->explanation)
        <div class="mt-8">
            <details class="group bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden transition-all duration-300 open:shadow-md" {{ $category !== 'MCQ' ? 'open' : '' }}>
                <summary class="list-none cursor-pointer p-5 flex justify-between items-center hover:bg-slate-50/80 transition-colors">
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.15em] flex items-center gap-3">
                        <span class="flex h-1.5 w-1.5 rounded-full bg-indigo-400"></span>
                        @if($category === 'MCQ') ব্যাখ্যা (Explanation) @else সমাধান (Solution) @endif
                    </h3>
                    <x-icons.down-arrow class="w-4 h-4 text-slate-400 group-open:rotate-180 transition-transform duration-300" />
                </summary>
                <div class="px-6 pb-6 text-sm text-slate-600 leading-relaxed animate-in fade-in slide-in-from-top-2 duration-500">
                    <div class="pt-4 border-t border-slate-100/80">
                        <div class="relative md:pl-4 py-1 prose prose-sm max-w-none">
                            {!! smart_nl2br($q->explanation) !!}
                        </div>
                    </div>
                </div>
            </details>
        </div>
        @endif
    </main>
</div>
@endsection

@push('scripts')
<script>
    // Only runs for MCQ
    function checkAnswer(element, selectedLabel) {
        const wrapper = document.getElementById('mcq-wrapper');
        if(!wrapper) return;

        const correctLabel = wrapper.getAttribute('data-correct').trim();
        const options = document.querySelectorAll('.mcq-option');

        options.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('opacity-70');
        });

        const isCorrect = selectedLabel === correctLabel;
        const statusIcon = element.querySelector('.status-icon');
        
        element.classList.remove('opacity-70');
        element.classList.add('!opacity-100', 'scale-[1.02]'); 
        statusIcon.classList.remove('hidden');

        if (isCorrect) {
            element.classList.replace('border-slate-100', 'border-green-500');
            element.classList.add('bg-green-50/30');
            statusIcon.innerHTML = `<div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center text-white text-[10px]">✓</div>`;
        } else {
            element.classList.replace('border-slate-100', 'border-red-500');
            element.classList.add('bg-red-50/30');
            statusIcon.innerHTML = `<div class="w-6 h-6 rounded-full bg-red-500 flex items-center justify-center text-white text-[10px]">✕</div>`;
            
            options.forEach(btn => {
                const label = btn.querySelector('.option-label').innerText.trim();
                if(label === correctLabel) {
                    btn.classList.remove('opacity-70');
                    btn.classList.add('!opacity-100', 'border-green-500', 'bg-green-50/30');
                    const correctIcon = btn.querySelector('.status-icon');
                    correctIcon.classList.remove('hidden');
                    correctIcon.innerHTML = `<div class="w-6 h-6 rounded-full border-2 border-green-500 flex items-center justify-center text-green-500 text-[10px]">✓</div>`;
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // 1. Track View
        fetch('{{ url("/auth/reading/track-view") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                post_id: {{ $q->id }},
                subject_id: '{{ $q->subject_id }}',
                institution_id: '{{ $q->institution_id }}'
            })
        }).catch(err => console.log('Tracking skipped'));

        // 2. AJAX Update Next Button URL (Priority Logic)
        @auth
        fetch('{{ url("/auth/get-next-question/" . $q->id) }}')
            .then(response => response.json())
            .then(data => {
                if (data.url) {
                    const nextBtn = document.getElementById('next-question-btn');
                    if(nextBtn) nextBtn.setAttribute('href', data.url);
                }
            })
            .catch(err => console.log('Priority next URL fetch failed, using default.'));
        @endauth
    });
</script>
@endpush