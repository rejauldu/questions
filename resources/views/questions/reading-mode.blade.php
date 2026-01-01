@extends('layout-simple')

@section('content')
<div class="min-h-screen bg-[#FDFCF8] text-[#334155] pb-32 font-sans antialiased">
    {{-- Top Slim Header --}}
    <header class="py-3 px-4 border-b border-slate-100 flex justify-between items-center max-w-3xl mx-auto sticky top-0 bg-[#FDFCF8]/90 backdrop-blur-sm z-50">
        <a href="{{ url('/') }}" class="text-slate-500 hover:text-primary-600 transition flex items-center gap-1 text-xs font-semibold">
            <x-icons.left-arrow class="w-3.5 h-3.5" /> HOME
        </a>
        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 flex items-center gap-2">
            @if($isRead)
                <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
            @endif
            {{ !empty($q->chapter) && $q->chapter !== 'None' ? $q->chapter : 'General Questions' }}
        </span>
    </header>

    <main class="max-w-2xl mx-auto pt-10 px-6">
        <article class="prose prose-slate max-w-none">
            {{-- Question Text --}}
            <h1 class="text-base sm:text-lg font-medium leading-relaxed text-slate-800 mb-8">
                {!! nl2br($q->article) !!}
            </h1>

            {{-- MCQ vs CQ Display Logic --}}
            @if(strtoupper($q->category) === 'MCQ')
                {{-- Data-correct now holds the value from the DB (e.g., 'ক') --}}
                <div id="mcq-wrapper" data-correct="{{ trim($q->ans) }}" class="grid grid-cols-1 gap-3 my-8">
                    @foreach(['a'=>'ক','b'=>'খ','c'=>'গ','d'=>'ঘ'] as $key => $label)
                        @if(!empty(trim(strip_tags($q->$key))))
                        <button onclick="checkAnswer(this, '{{ $label }}')" 
                                class="mcq-option flex items-start gap-3 p-4 rounded-xl border border-slate-200 bg-white hover:border-primary-300 transition-all text-left w-full group">
                            <span class="option-label flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg bg-slate-50 text-slate-500 font-bold text-xs group-hover:bg-primary-50 transition-colors">
                                {{ $label }}
                            </span>
                            <div class="text-sm text-slate-700 pt-0.5">{!! $q->$key !!}</div>
                        </button>
                        @endif
                    @endforeach
                </div>
            @else
                {{-- CQ / Writing: Non-clickable rows --}}
                <div class="space-y-4 my-8">
                    @foreach(['a'=>'ক','b'=>'খ','c'=>'গ','d'=>'ঘ'] as $key => $label)
                        @if(!empty(trim(strip_tags($q->$key))))
                        <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                            <span class="font-bold text-primary-600 text-sm">{{ $label }})</span>
                            <div class="text-sm text-slate-700 leading-relaxed">{!! $q->$key !!}</div>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- Images --}}
            <div class="space-y-6 my-8">
                @foreach(['image1', 'image2', 'image3', 'image4'] as $img)
                    @if($q->$img)
                        <img src="{{ asset($q->$img) }}" class="w-full h-auto rounded-xl border border-slate-200 shadow-sm" />
                    @endif
                @endforeach
            </div>
        </article>

        @if($q->ans)
        {{-- 1. Answer Reveal Section --}}
        <div class="mt-12 py-6 border-t border-slate-100">
            <div id="answer-box" class="hidden animate-in fade-in zoom-in duration-300 mb-8">
                <div class="flex items-center gap-3 p-4 bg-primary-50 rounded-2xl border border-primary-100">
                    <div id="feedback-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold"></div>
                    <div>
                        <p id="feedback-text" class="text-xs font-bold uppercase tracking-widest text-primary-700"></p>
                        <p class="text-lg font-black text-slate-800">সঠিক উত্তর: 
                            <span class="text-primary-600">{{ (strtoupper($q->category) === 'MCQ') ? $q->ans : ($q->answer->text ?? "") }}</span>
                        </p>
                    </div>
                </div>
            </div>
            
            @if(strtoupper($q->category) !== 'MCQ')
                <button onclick="document.getElementById('answer-box').classList.remove('hidden'); this.remove();" 
                        class="w-full py-3 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-xs hover:bg-slate-50 transition-all uppercase tracking-widest">
                    উওর দেখুন
                </button>
            @endif
        </div>
        @endif

        {{-- 2. Navigation Bar --}}
        <div class="flex items-center justify-between gap-4 mb-12">
            <a href="{{ $prevUrl }}" class="flex-1 flex items-center justify-center gap-2 py-4 rounded-2xl bg-white border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-all shadow-sm">
                <x-icons.left-arrow class="w-3.5 h-3.5" /> PREVIOUS
            </a>
            <a href="{{ $nextUrl }}" class="flex-1 flex items-center justify-center gap-2 py-4 rounded-2xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-800 transition-all shadow-lg active:scale-95">
                NEXT QUESTION <x-icons.down-arrow class="w-3.5 h-3.5 -rotate-90" />
            </a>
        </div>

        {{-- 3. Explanation Section (Closed by default) --}}
        @if($q->explanation)
        <div class="mt-8">
            <details class="group bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden transition-all duration-300 open:shadow-md open:ring-1 open:ring-slate-100">
                <summary class="list-none cursor-pointer p-5 flex justify-between items-center hover:bg-slate-50/80 transition-colors">
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.15em] flex items-center gap-3">
                        <span class="flex h-1.5 w-1.5 rounded-full bg-indigo-400"></span>
                        ব্যাখ্যা (Explanation)
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-medium text-slate-300 uppercase group-open:hidden">View</span>
                        <x-icons.down-arrow class="w-4 h-4 text-slate-400 group-open:rotate-180 transition-transform duration-300" />
                    </div>
                </summary>
                
                <div class="px-6 pb-6 text-sm text-slate-600 leading-relaxed animate-in fade-in slide-in-from-top-2 duration-500">
                    <div class="pt-4 border-t border-slate-100/80">
                        <div class="relative md:pl-4 border-l-2 border-indigo-50 py-1">
                            {!! nl2br($q->explanation) !!}
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
    function checkAnswer(element, selectedLabel) {
        const wrapper = document.getElementById('mcq-wrapper');
        const correctLabel = wrapper.getAttribute('data-correct').trim();
        
        const ui = {
            box: document.getElementById('answer-box'),
            icon: document.getElementById('feedback-icon'),
            text: document.getElementById('feedback-text'),
            options: document.querySelectorAll('.mcq-option')
        };

        // 1. Disable all options to prevent double-clicking
        ui.options.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('opacity-60');
        });

        // 2. Highlight selected option
        element.classList.remove('opacity-60');
        element.disabled = false;
        ui.box.classList.remove('hidden');

        // 3. Logic: Compare Bengali Label
        const isCorrect = selectedLabel === correctLabel;
        
        if (isCorrect) {
            element.classList.add('border-green-500', 'bg-green-50', '!opacity-100');
            ui.icon.className = "w-10 h-10 rounded-full flex items-center justify-center text-white font-bold bg-green-500 shadow-sm";
            ui.icon.innerHTML = "✓";
            ui.text.innerText = "অভিনন্দন! আপনার উত্তরটি সঠিক।";
        } else {
            element.classList.add('border-red-500', 'bg-red-50', '!opacity-100');
            ui.icon.className = "w-10 h-10 rounded-full flex items-center justify-center text-white font-bold bg-red-500 shadow-sm";
            ui.icon.innerHTML = "✕";
            ui.text.innerText = "দুঃখিত! আপনার উত্তরটি ভুল।";
        }

        // 4. Smooth Scroll to Feedback
        ui.box.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // 5. OPTIONAL: Save progress to Server
        // fetch('/api/user/save-progress', { 
        //    method: 'POST', 
        //    body: JSON.stringify({ q_id: {{ $q->id }}, correct: isCorrect }),
        //    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        // });
    }
</script>
<script>
    // Existing checkAnswer logic here...

    // Track View Logic
    document.addEventListener('DOMContentLoaded', function() {
        @auth
        // Wait 3 seconds to ensure they are actually reading
        setTimeout(() => {
            fetch('{{ url("/auth/reading/track-view") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ post_id: {{ $q->id }} })
            })
            .catch(err => console.error('Tracking failed', err));
        }, 3000);
        @endauth
    });
</script>
@endpush