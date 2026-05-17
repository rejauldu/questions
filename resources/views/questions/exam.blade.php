@extends('layout')

@section('seo')
<title>{{ $displayName . ($category ? " $category" : "") . " - ExamDao" }}</title>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="max-w-6xl mx-auto px-1 md:px-2">
        <div class="sticky top-0 bg-white/90 backdrop-blur-xl z-40 shadow-sm">
            <div class="bg-white rounded-md shadow-sm mb-2 md:mb-4">
                
                {{-- Row 1: Selectors --}}
                <div class="flex gap-2 p-2 md:p-4 pb-0 md:pb-0">
                    <select id="cat" onchange="submitFilters()" class="flex-1 bg-slate-50 border-slate-200 rounded-xl text-sm font-bold text-slate-700 py-2 px-3">
                        <option value="">Category</option>
                        @foreach(['CQ', 'MCQ', 'Writing'] as $cat)
                            @if($availableCategories->contains($cat))
                                <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endif
                        @endforeach
                    </select>
                    
                    <select id="chap" onchange="submitFilters()" class="flex-1 bg-slate-50 border-slate-200 rounded-xl text-sm font-bold text-slate-700 py-2 px-3">
                        <option value="">Chapter</option>
                        @foreach($availableChapters as $ch)
                            <option value="{{ $ch }}" {{ request('chapter') == $ch ? 'selected' : '' }}>Ch-{{ $ch }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Row 2: Subject Scroll with Search & Indicators --}}
                <div class="relative flex items-center border-slate-100 pt-3">
                    
                    {{-- Mobile Scroll Indicators --}}
                    <div class="absolute left-0 top-3 bottom-0 w-3 bg-gradient-to-r from-slate-200 to-transparent md:hidden z-30 pointer-events-none"></div>
                    <div class="absolute right-0 top-3 bottom-0 w-3 bg-gradient-to-l from-slate-200 to-transparent md:hidden z-30 pointer-events-none"></div>
                    <div class="absolute top-3 left-0 w-full h-[2px] bg-slate-300 md:hidden z-30"></div>
                    <div class="absolute bottom-0 left-0 w-full h-[2px] bg-slate-300 md:hidden z-30"></div>

                    {{-- Desktop Left Arrow --}}
                    <button id="left-arrow" class="hidden md:flex absolute -left-4 z-50 w-8 h-8 items-center justify-center bg-white border border-slate-200 rounded-full shadow-md text-slate-500 hover:bg-emerald-600 hover:text-white transition-all hover:scale-110">‹</button>

                    <div id="sub-scroll" class="flex-1 overflow-x-auto no-scrollbar scroll-smooth flex items-center gap-3 py-2 px-2">
                        
                        {{-- Search Input --}}
                        <div class="shrink-0">
                            <input type="text" id="subject-search" placeholder="Search..." 
                                class="w-28 px-3 py-1.5 text-sm border border-slate-200 rounded-lg focus:ring-1 focus:ring-emerald-500 outline-none">
                        </div>

                        {{-- DYNAMIC SUBJECTS ONLY (The 'All' button was removed from here) --}}
                        @foreach($subjects as $sub)
                            <a href="{{ url('/exam/' . $institution->slug . '/' . $sub->slug) }}{{ $category ? '/' . $category : '' }}{{ request('chapter') ? '?chapter=' . request('chapter') : '' }}"
                            class="filter-item shrink-0 px-4 py-1.5 rounded-lg text-sm font-bold border transition-all {{ $selectedSub == $sub->slug ? 'bg-emerald-600 text-white border-emerald-600' : 'border-slate-200' }}"
                            data-bangla="{{ $sub->bangla }}" 
                            data-english="{{ $sub->name }}" 
                            data-banglish="{{ $sub->banglish }}">
                            {{ $sub->bangla }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Desktop Right Arrow --}}
                    <button id="right-arrow" class="hidden md:flex absolute -right-4 z-50 w-8 h-8 items-center justify-center bg-white border border-slate-200 rounded-full shadow-md text-slate-500 hover:bg-emerald-600 hover:text-white transition-all hover:scale-110">›</button>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="space-y-4">
            @includeWhen($posts->count(), 'partials.post-loop')
        </div>
    </div>
</div>

<script>
    // Only this function remains because it handles the logic for the Select dropdowns.
    function submitFilters() {
        const cat = document.getElementById('cat')?.value;
        const chap = document.getElementById('chap')?.value;
        const sub = '{{ $selectedSub }}';
        
        let url = `/exam/{{ $institution->slug }}/${sub}`;
        if (cat) url += `/${cat}`;
        if (chap) url += `?chapter=${chap}`;
        
        document.body.style.opacity = '0.7';
        window.location.href = url;
    }
</script>
@endsection