@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 py-4 sm:px-4 sm:py-8 bg-white min-h-screen text-slate-700">
    <div class="mb-6 px-1">
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-indigo-600 mb-1">
            <span>HSC English 2nd</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 8</span>
        </nav>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Synonyms & Antonyms</h1>
    </div>

    <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 mb-8 rounded-r-xl shadow-sm">
        <h3 class="text-indigo-900 font-black text-sm uppercase mb-1">কৌশল (General Techniques):</h3>
        <ul class="text-indigo-900 text-sm sm:text-base space-y-2 list-disc pl-5 leading-snug">
            <li>মূল শব্দটি প্যাসেজে কোন Context-এ ব্যবহৃত হয়েছে তা বুঝে সমার্থক বা বিপরীত শব্দ লেখো।</li>
            <li>Parts of Speech পরিবর্তন না করে উত্তর দেওয়ার চেষ্টা করো (যেমন: Verb থাকলে উত্তরটিও যেন Verb হয়)।</li>
        </ul>
    </div>

    <section class="mb-10 px-1 space-y-4">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-2">8. Read the passage and then write the antonym or synonym of the words as directed:</h2>
        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-inner leading-relaxed text-slate-800 text-sm sm:text-lg italic">
            There can be no progress without efforts. Life loses its interest if there is no struggle. For example, games become dull if there is no competition in them and if the result is easily foreseen. No matter we win the game or lose it. The keener the contest, the greater the enjoyment. A victory is not a real triumph unless both the sides are equally matched. Whether we like it or not, life is a continuous competitive examination.
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mt-6 text-xs sm:text-sm font-bold text-indigo-700">
            <span>(a) progress (synonym);</span>
            <span>(b) efforts (synonym);</span>
            <span>(c) interest (synonym);</span>
            <span>(d) struggle (antonym);</span>
            <span>(e) dull (antonym);</span>
            <span>(f) competition (synonym);</span>
            <span>(g) easily (antonym);</span>
            <span>(h) win (antonym);</span>
            <span>(i) enjoyment (synonym);</span>
            <span>(j) victory (antonym);</span>
            <span>(k) real (synonym);</span>
            <span>(l) equally (antonym);</span>
            <span>(m) like (synonym);</span>
            <span>(n) continuous (antonym).</span>
        </div>
    </section>

    

    <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <h2 class="mb-4 italic border-b-2 border-indigo-400 pb-1 inline-block text-lg">Ans to the Q. no-8</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-y-3 font-mono text-xs sm:text-sm mt-2">
            <div>(a) advancement</div>
            <div>(b) attempts</div>
            <div>(c) eagerness</div>
            <div>(d) ease</div>
            <div>(e) lively</div>
            <div>(f) contest</div>
            <div>(g) difficultly</div>
            <div>(h) lose</div>
            <div>(i) pleasure</div>
            <div>(j) defeat</div>
            <div>(k) genuine</div>
            <div>(l) unequally</div>
            <div>(m) prefer</div>
            <div>(n) interrupted</div>
        </div>
    </div>
</div>
@endsection