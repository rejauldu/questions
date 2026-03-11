@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 py-4 sm:px-4 sm:py-8 bg-white min-h-screen text-slate-700">
    <div class="mb-6 px-1">
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-indigo-600 mb-1">
            <span>HSC English 2nd</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 6</span>
        </nav>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Modifiers</h1>
    </div>

    <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 mb-8 rounded-r-xl shadow-sm">
        <h3 class="text-indigo-900 font-black text-sm uppercase mb-1">কৌশল (General Techniques):</h3>
        <ul class="text-indigo-900 text-sm sm:text-base space-y-2 list-disc pl-5 leading-snug">
            <li>ব্র্যাকেটে দেওয়া গ্রামাটিক্যাল ডিরেকশন (যেমন: Pre-modify বা Post-modify) অনুযায়ী সঠিক শব্দ বসাও।</li>
            <li>বাক্যের প্রসঙ্গের সাথে সামঞ্জস্যপূর্ণ অর্থপূর্ণ Modifier নির্বাচন করো।</li>
        </ul>
    </div>

    <section class="mb-10 px-1 space-y-4">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-2">6. Read the following text and use modifiers in the blank spaces as directed:</h2>
        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-inner leading-[2.5] text-slate-800 text-sm sm:text-lg italic">
            The necessity of learning English is the demand of today’s world. But many people in (a) <span class="tracking-tighter">__________</span> (Pre-modify the noun with a possessive) country do not realize it. 
            As we are living in a (b) <span class="tracking-tighter">__________</span> (Pre-modify the noun with an adjective) village, it has become essential (c) <span class="tracking-tighter">__________</span> (Post-modify the adjective with an infinitive phrase). 
            So, if we know English (d) <span class="tracking-tighter">__________</span> (Post-modify the verb with an adverb), we can communicate with people all over the world. 
            Surely, English has become an important element of our (e) <span class="tracking-tighter">__________</span> (Use an adjective to pre-modify the noun) development. 
            This language ensures an (f) <span class="tracking-tighter">__________</span> (Use an adjective to pre-modify the noun) access to all the information in the world computers. 
            (g) <span class="tracking-tighter">__________</span> (Use a participle phrase to pre-modify the clause), we can progress personally and professionally. 
            If we fail to have a good command over the language, we will lag behind (h) <span class="tracking-tighter">__________</span> (Post-modify the verb using an adverbial). 
            With (i) <span class="tracking-tighter">__________</span> (Use a demonstrative to pre-modify the noun) language, we can get access to art, literature and culture from various countries. 
            So, it is clear that English is (j) <span class="tracking-tighter">__________</span> (Use an adverb to pre-modify the verb) spoken all over the world.
        </div>
    </section>

    

    <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <h2 class="mb-4 italic border-b-2 border-indigo-400 pb-1 inline-block text-lg">Ans to the Q. no-6</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-y-3 font-mono text-xs sm:text-sm mt-2">
            <div>(a) our</div>
            <div>(b) global</div>
            <div class="sm:col-span-2">(c) to communicate with people...</div>
            <div>(d) accurately</div>
            <div>(e) national</div>
            <div>(f) easy</div>
            <div class="sm:col-span-2">(g) Learning English</div>
            <div>(h) badly</div>
            <div>(i) this</div>
            <div>(j) widely</div>
        </div>
    </div>
</div>
@endsection