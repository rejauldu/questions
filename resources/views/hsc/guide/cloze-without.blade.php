@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-3 py-4 sm:py-8 bg-white shadow-sm rounded-xl sm:rounded-2xl border border-slate-100">
    <nav class="text-[10px] sm:text-[11px] font-bold mb-4 uppercase tracking-widest px-1 flex justify-between items-center border-b border-slate-50 pb-2">
        <div class="flex items-center gap-2">
            <span class="text-indigo-600">HSC English 1st</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 5</span>
        </div>
    </nav>
    
    <header class="mb-6 px-1">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mb-1 uppercase italic underline decoration-emerald-500 decoration-4 underline-offset-4">
            Cloze Test Without Clues
        </h1>
    </header>

    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-600 rounded-r-2xl p-4 sm:p-5 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="bg-emerald-600 text-white p-2 rounded-lg shrink-0 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-emerald-900 font-black text-sm sm:text-base uppercase tracking-tight mb-1 italic underline decoration-emerald-200">কৌশল (Pro Tip):</h4>
                <p class="text-emerald-800 text-xs sm:text-[13px] leading-snug font-medium">
                    এক্ষেত্রে কোনো Clue দেওয়া থাকে না। তাই বাক্যটির অর্থ বুঝে সঠিক <b>Grammar</b> ও <b>Context</b> অনুযায়ী শব্দ বসাতে হবে। বিশেষ করে Article, Preposition এবং সমার্থক শব্দের দিকে খেয়াল রাখো।
                </p>
            </div>
        </div>
    </div>

    <section class="mb-8 px-1">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-3 leading-snug">
            5. Fill in the blanks with appropriate words to make the passage a meaningful one:
        </h2>
        <div class="p-5 sm:p-8 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 text-sm sm:text-base leading-[2.2] text-justify font-serif italic shadow-inner">
            The world is producing millions of tons of domestic rubbish and 
            <span class="border-b-2 border-slate-300 px-3 font-bold text-indigo-600">(a) ________</span> 
            industrial waste each year. It is becoming very difficult to find 
            <span class="border-b-2 border-slate-300 px-3 font-bold text-indigo-600">(b) ________</span> 
            locations to 
            <span class="border-b-2 border-slate-300 px-3 font-bold text-indigo-600">(c) ________</span> 
            rid of all these refuse. The dumping of 
            <span class="border-b-2 border-slate-300 px-3 font-bold text-indigo-600">(d) ________</span> 
            kinds of waste is seriously 
            <span class="border-b-2 border-slate-300 px-3 font-bold text-indigo-600">(e) ________</span> 
            the environment. We know that air is a/an 
            <span class="border-b-2 border-slate-300 px-3 font-bold text-indigo-600">(f) ________</span> 
            element of our environment. But the air is polluted by 
            <span class="border-b-2 border-slate-300 px-3 font-bold text-indigo-600">(g) ________</span>. 
            Water, another vital element of the environment, is polluted by different kinds of waste and filth. If we want to live a 
            <span class="border-b-2 border-slate-300 px-3 font-bold text-indigo-600">(h) ________</span> 
            life, we should prevent the pollution of the environment. Total prevention may be 
            <span class="border-b-2 border-slate-300 px-3 font-bold text-indigo-600">(i) ________</span> 
            but we can certainly reduce pollution by raising 
            <span class="border-b-2 border-slate-300 px-3 font-bold text-indigo-600">(j) ________</span> 
            among people.
        </div>
    </section>

    

    <section class="bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border-t-4 border-emerald-600">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 px-6 py-3 text-white flex justify-between items-center">
            <h3 class="font-black uppercase tracking-widest text-xs italic">Answer Script for Exam</h3>
            <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded text-emerald-100 uppercase font-bold">Marks: 1 × 10 = 10</span>
        </div>
        
        <div class="p-6 font-mono text-sm text-slate-300">
            <p class="font-bold text-center underline text-white mb-6 italic text-sm">Ans to the Q. no-5</p>
            
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-y-3 gap-x-2">
                @php 
                    $answers = [
                        'a' => 'harmful', 'b' => 'suitable', 'c' => 'get', 'd' => 'various', 'e' => 'polluting',
                        'f' => 'essential', 'g' => 'smoke', 'h' => 'healthy', 'i' => 'impossible', 'j' => 'awareness'
                    ];
                @endphp
                @foreach($answers as $key => $ans)
                <div class="flex items-center justify-center gap-2 border border-white/10 py-1.5 rounded-lg bg-white/5 transition-all hover:bg-emerald-900/30">
                    <span class="text-emerald-400 font-bold">({{ $key }})</span>
                    <span class="text-amber-400 font-bold uppercase">{{ $ans }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection