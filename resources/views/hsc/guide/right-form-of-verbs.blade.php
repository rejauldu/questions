@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 py-4 sm:px-4 sm:py-8 bg-white min-h-screen text-slate-700">
    <div class="mb-6 px-1">
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-indigo-600 mb-1">
            <span>HSC English 2nd</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 4</span>
        </nav>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Right Form of Verbs</h1>
    </div>

    <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 mb-8 rounded-r-xl shadow-sm">
        <h3 class="text-indigo-900 font-black text-sm uppercase mb-1">কৌশল (General Techniques):</h3>
        <ul class="text-indigo-900 text-sm sm:text-base space-y-2 list-disc pl-5 leading-snug">
            <li>পুরো প্যাসেজটি পড়ে এর প্রধান Tense (Present/Past) শনাক্ত করো।</li>
            <li>Subject অনুযায়ী Verb-এর Number ও Person ঠিক আছে কি না যাচাই করো।</li>
        </ul>
    </div>

    <section class="mb-10 px-1 space-y-4">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-2">4. Read the text and fill in the gaps with the correct forms of verbs as per subject and context:</h2>
        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-inner leading-[2.5] text-slate-800 text-sm sm:text-lg italic">
            In this world truth (a) <span class="tracking-tighter">__________</span> (reign) over falsehood. 
            Those who are always in the habit of (b) <span class="tracking-tighter">__________</span> (speak) the truth (c) <span class="tracking-tighter">__________</span> (respect) everywhere. 
            But those who are involved in telling lies, (d) <span class="tracking-tighter">__________</span> (not shine) in life. 
            That is why, we see that many great men in history used to (e) <span class="tracking-tighter">__________</span> (preach) truth among their countrymen. 
            (f) <span class="tracking-tighter">__________</span> (Be) truthful is essential for our society. 
            It (g) <span class="tracking-tighter">__________</span> (hold) the highest value in (h) <span class="tracking-tighter">__________</span> (make) our society better. 
            This quality (i) <span class="tracking-tighter">__________</span> (consider) as one of the essential factors that empower us from within. 
            A truthful man is a (j) <span class="tracking-tighter">__________</span> (trust) person. 
            And this trust (k) <span class="tracking-tighter">__________</span> (build) confidence in us. 
            Confidence makes us (l) <span class="tracking-tighter">__________</span> (feel) better. 
            When we start (m) <span class="tracking-tighter">__________</span> (get) comfortable with speaking the truth, we will begin to prosper. 
            So, we should try hard (n) <span class="tracking-tighter">__________</span> (stay) away from the lies.
        </div>
    </section>

    

    <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <h2 class="mb-4 italic border-b-2 border-indigo-400 pb-1 inline-block text-lg">Ans to the Q. no-4</h2>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-y-3 font-mono text-xs sm:text-sm mt-2">
            <div>(a) reigns</div>
            <div>(b) speaking</div>
            <div>(c) are respected</div>
            <div>(d) don’t shine</div>
            <div>(e) preach</div>
            <div>(f) Being</div>
            <div>(g) holds</div>
            <div>(h) making</div>
            <div>(i) is considered</div>
            <div>(j) trusted</div>
            <div>(k) builds</div>
            <div>(l) feel</div>
            <div>(m) getting / to get</div>
            <div>(n) to stay</div>
        </div>
    </div>
</div>
@endsection