@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 py-4 sm:px-4 sm:py-8 bg-white min-h-screen">
    <div class="mb-6 px-1">
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-indigo-600 mb-1">
            <span>HSC English 2nd</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 1</span>
        </nav>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Appropriate Prepositions</h1>
    </div>

    <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 mb-8 rounded-r-xl shadow-sm">
        <h3 class="text-indigo-900 font-black text-sm uppercase mb-1">কৌশল (General Techniques):</h3>
        <ul class="text-indigo-900 text-sm sm:text-base space-y-2 list-disc pl-5 leading-snug">
            <li>Appropriate Preposition-এর তালিকার ওপর দখল থাকলে এই প্রশ্নে ভালো করা সম্ভব।</li>
            <li>বাক্যের অর্থ বুঝে সময়, স্থান বা কারণ অনুযায়ী সঠিক শব্দ বসাতে হবে।</li>
        </ul>
        <div class="flex justify-between items-center mt-3 border-t border-indigo-200 pt-2">
            <p class="text-indigo-700 text-[11px] font-bold uppercase tracking-wider italic">Marks: $0.5 \times 10 = 05$</p>
        </div>
    </div>

    <section class="mb-10 px-1 space-y-4">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-2">1. Complete the text with suitable prepositions:</h2>
        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-inner leading-[2.5] text-slate-800 text-sm sm:text-lg italic">
            Charity is a noble virtue. A person should be endowed (a) <span class="tracking-tighter">__________</span> this virtue. 
            It makes people think favourably (b) <span class="tracking-tighter">__________</span> their fellowmen and do them good. 
            It is also the cheerful giving of one's possession to someone (c) <span class="tracking-tighter">__________</span> need. 
            This quality brings happiness (d) <span class="tracking-tighter">__________</span> earth and strengthens the ties of relationship (e) <span class="tracking-tighter">__________</span> men. 
            It should not be measured (f) <span class="tracking-tighter">__________</span> terms of money. 
            Rather, it should be measured (g) <span class="tracking-tighter">__________</span> the sacrifice that one makes. 
            In fact, it is a form (h) <span class="tracking-tighter">__________</span> self-sacrifice (i) <span class="tracking-tighter">__________</span> which our society cannot progress. 
            So, everybody should practise this habit (j) <span class="tracking-tighter">__________</span> childhood.
        </div>
    </section>

    

    <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <h2 class="mb-4 italic border-b-2 border-indigo-400 pb-1 inline-block">Ans to the Q. no-1</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-y-3 font-mono text-xs sm:text-sm">
            <div>(a) with</div>
            <div>(b) of</div>
            <div>(c) in</div>
            <div>(d) on</div>
            <div>(e) among</div>
            <div>(f) in</div>
            <div>(g) by</div>
            <div>(h) of</div>
            <div>(i) without</div>
            <div>(j) from</div>
        </div>
    </div>
</div>
@endsection