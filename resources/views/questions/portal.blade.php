@extends('layout')

@section('seo')
@php
    $seoTitle = $displayName . ($category ? " $category" : "") . " - ExamDao";
@endphp
<title>{{ $seoTitle }}</title>
@endsection

@section('content')
<div class="min-h-screen bg-white py-4 md:py-8">
    <div class="max-w-6xl mx-auto px-4">
        
        {{-- Filters & Actions Stack --}}
        <div class="flex flex-col gap-4 mb-8">
            
            {{-- 1. Filter Fields (Top) --}}
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
                @php
                    // Detection for HSC to show/hide Chapter filter
                    $isHsc = (strtolower($institution->slug) === 'hsc');
                @endphp

                <form id="filterForm" onsubmit="return handlePortalSearch(event)" 
                    class="grid {{ $isHsc ? 'grid-cols-4' : 'grid-cols-3' }} gap-3">
                    
                    {{-- Subject Dropdown --}}
                    <div class="col-span-2">
                        <label for="sub" class="block text-[10px] font-bold text-slate-400 uppercase mb-1 ml-1 tracking-widest">Subject</label>
                        <select id="sub" onchange="updateButtonText(true)" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-primary-500 focus:border-primary-500 py-2.5 transition-all">
                            <option value="all" data-name="">All Subjects</option>
                            @foreach($subjects as $sub)
                                @php 
                                    $genericName = trim(preg_replace('/\s+(1st|2nd|১ম|২য়)$/iu', '', $sub->name));
                                    $gSlug = url_slug($genericName); 
                                @endphp
                                <option value="{{ $gSlug }}" data-name="{{ $genericName }}" {{ $selectedSub == $gSlug ? 'selected' : '' }}>
                                    {{ $genericName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Category Dropdown --}}
                    <div class="col-span-1">
                        <label for="cat" class="block text-[10px] font-bold text-slate-400 uppercase mb-1 ml-1 tracking-widest">Category</label>
                        <select id="cat" onchange="updateButtonText(true)" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-primary-500 focus:border-primary-500 py-2.5 uppercase">
                            <option value="">All</option>
                            <option value="CQ" {{ $category == 'CQ' ? 'selected' : '' }}>CQ</option>
                            <option value="MCQ" {{ $category == 'MCQ' ? 'selected' : '' }}>MCQ</option>
                            <option value="Writing" {{ $category == 'Writing' ? 'selected' : '' }}>Writing</option>
                        </select>
                    </div>

                    {{-- Chapter Dropdown (HSC ONLY) --}}
                    @if($isHsc)
                    <div class="col-span-1">
                        <label for="chap" class="block text-[10px] font-bold text-slate-400 uppercase mb-1 ml-1 tracking-widest">Chapter</label>
                        <select id="chap" onchange="updateButtonText(true)" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-primary-500 focus:border-primary-500 py-2.5">
                            <option value="">All</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('chapter') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    @endif
                </form>
            </div>

            {{-- 2. Buttons Row: Right Aligned & Compact --}}
            <div class="flex justify-end items-center gap-2 md:gap-3">
                
                {{-- Search Button --}}
                <button type="submit" form="filterForm" class="flex-none bg-primary-600 hover:bg-primary-700 text-white px-4 md:px-6 py-3 rounded-2xl transition-all shadow-md flex items-center justify-center gap-2 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="font-bold text-sm xs:block">Search</span>
                </button>

                {{-- Dynamic Practice Button --}}
                @if($posts->isNotEmpty())
                    @php
                        $first = $posts->first();
                        $readUrl = route('reading.mode', [
                            $institution->slug, 
                            $selectedSub ?: 'all', 
                            $first->id, 
                            url_slug(strip_tags($first->article))
                        ]);
                    @endphp
                    <a href="{{ $readUrl }}" id="practiceLink" class="flex-none group flex items-center gap-2 md:gap-3 bg-slate-900 rounded-2xl px-4 md:px-5 py-3 hover:bg-slate-800 transition-all duration-300 border border-slate-800 shadow-xl max-w-[190px] xs:max-w-[260px]">
                        <h3 id="dynamicBtnText" class="text-white font-bold text-xs md:text-sm truncate">
                            Start Practice
                        </h3>
                        <svg class="w-4 h-4 text-primary-500 group-hover:translate-x-1 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        {{-- Content List --}}
        <div class="space-y-4">
            @if($posts->count())
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-1.5 md:p-5">
                    @include('partials.post-loop')
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 text-sm font-medium">No questions found for this selection.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    /**
     * Updates Practice button text based on selection
     */
    function updateButtonText(userInteracted = false) {
        const subSelect = document.getElementById('sub');
        const catSelect = document.getElementById('cat');
        const chapSelect = document.getElementById('chap'); // Exists only for HSC
        const btnText = document.getElementById('dynamicBtnText');
        
        if (!btnText) return;

        const selectedSubName = subSelect.options[subSelect.selectedIndex].getAttribute('data-name') || "";
        const selectedCat = catSelect.value !== "" ? catSelect.options[catSelect.selectedIndex].text : "";
        const selectedChap = (chapSelect && chapSelect.value !== "") ? " Ch-" + chapSelect.value : "";
        
        if (!userInteracted && !selectedSubName && !selectedCat && !selectedChap) {
            btnText.innerText = "Start Practice";
            return;
        }

        if (!selectedSubName && !selectedCat && !selectedChap) {
            btnText.innerText = "Start Practice";
        } else {
            let displayText = "Practice " + selectedSubName;
            if (selectedCat) displayText += " " + selectedCat;
            if (selectedChap) displayText += selectedChap;
            btnText.innerText = displayText.trim();
        }
    }

    /**
     * Redirect logic: Institution/Subject/Category + ?chapter=X
     */
    function handlePortalSearch(event) {
        event.preventDefault();
        const subject = document.getElementById('sub').value;
        const category = document.getElementById('cat').value;
        const chapElem = document.getElementById('chap');
        const instSlug = "{{ $institution->slug }}";
        
        // Construct the Base Clean URL
        let targetUrl = `/exam/${instSlug}/${subject}`;
        if (category) targetUrl += `/${category}`;
        
        // Append Chapter as Query Parameter
        if (chapElem && chapElem.value !== "") {
            targetUrl += `?chapter=${chapElem.value}`;
        }
        
        window.location.href = targetUrl;
        return false;
    }

    // Run on load to set initial button state
    window.addEventListener('DOMContentLoaded', () => {
        updateButtonText(false); 
    });
</script>
@endsection