@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 py-4 sm:px-4 sm:py-8 bg-white min-h-screen text-slate-700">
    <div class="mb-6 px-1">
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-indigo-600 mb-1">
            <span>HSC English 2nd</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 2</span>
        </nav>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Special Words & Phrases</h1>
    </div>

    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-8 rounded-r-xl shadow-sm">
        <h3 class="text-amber-900 font-black text-sm uppercase mb-1">কৌশল (General Techniques):</h3>
        <ul class="text-amber-900 text-sm sm:text-base space-y-2 list-disc pl-5 leading-snug">
            <li>বক্স থেকে উপযুক্ত ফ্রেজ নির্বাচন করে বাক্যটির অর্থপূর্ণ প্রয়োগ নিশ্চিত করো।</li>
            <li>গ্যাপের পরের গ্রামাটিক্যাল স্ট্রাকচার খেয়াল করো।</li>
        </ul>
    </div>

    <div class="mb-8 p-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50">
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-center">
            @php $phrases = ['let alone', 'have to', 'it', 'was born', 'as soon as', 'there', "what's ... like", 'what does ... look like', 'had better', 'would rather']; @endphp
            @foreach($phrases as $phrase)
                <span class="bg-white border border-slate-200 py-1 px-2 rounded-lg text-[10px] sm:text-xs font-bold text-slate-600 shadow-sm">{{ $phrase }}</span>
            @endforeach
        </div>
    </div>

    <section class="mb-10 px-1 space-y-4">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-2">2. Complete the sentences with suitable words/phrases given in the box:</h2>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-slate-800 text-sm sm:text-base leading-relaxed italic">
            <p>(a) You <span class="border-b-2 border-indigo-200 px-8"></span> not touch the crocodile. It may be dangerous.</p>
            <p class="mt-4">(b) She has never boiled an egg, <span class="border-b-2 border-indigo-200 px-8"></span> prepared an entire meal.</p>
            <p class="mt-4">(c) It is raining outside. He will go to the shop <span class="border-b-2 border-indigo-200 px-8"></span> the rain stops.</p>
            <p class="mt-4">(d) I don’t need a lift. I <span class="border-b-2 border-indigo-200 px-8"></span> walk.</p>
            <p class="mt-4">(e) They <span class="border-b-2 border-indigo-200 px-8"></span> solve these problems soon or the project will fail.</p>
            <p class="mt-4">(f) The house was dirty. <span class="border-b-2 border-indigo-200 px-8"></span> was John who cleaned the house.</p>
            <p class="mt-4">(g) <span class="border-b-2 border-indigo-200 px-8"></span> goes a proverb that morning shows the day.</p>
            <p class="mt-4">(h) <span class="border-b-2 border-indigo-200 px-4"></span> an alligator <span class="border-b-2 border-indigo-200 px-4"></span>? I have never seen it.</p>
            <p class="mt-4">(i) He <span class="border-b-2 border-indigo-200 px-8"></span> of German parents. In fact, he lived most of his life abroad.</p>
            <p class="mt-4">(j) <span class="border-b-2 border-indigo-200 px-4"></span> the journey <span class="border-b-2 border-indigo-200 px-4"></span>? It was very enjoyable to me.</p>
        </div>
    </section>

    

    <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <h2 class="mb-4 italic border-b-2 border-indigo-400 pb-1 inline-block text-lg">Ans to the Q. no-2</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-y-3 font-mono text-xs sm:text-sm mt-2">
            <div>(a) had better</div>
            <div>(b) let alone</div>
            <div>(c) as soon as</div>
            <div>(d) would rather</div>
            <div>(e) have to</div>
            <div>(f) It</div>
            <div>(g) There</div>
            <div class="col-span-1 md:col-span-2">(h) what does ... look like</div>
            <div>(i) was born</div>
            <div class="col-span-1 md:col-span-2">(j) what's ... like</div>
        </div>
    </div>
</div>
@endsection