@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-3 py-4 sm:py-8 bg-white shadow-sm rounded-xl sm:rounded-2xl border border-slate-100">
    <nav class="text-[10px] sm:text-[11px] font-bold mb-4 uppercase tracking-widest px-1 flex justify-between items-center border-b border-slate-50 pb-2">
        <div class="flex items-center gap-2">
            <span class="text-indigo-600">HSC English 1st</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 3</span>
        </div>
    </nav>
    
    <header class="mb-6 sm:mb-8 px-1">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mb-1 uppercase italic underline decoration-indigo-500 decoration-4 underline-offset-4">
            Summary Writing
        </h1>
    </header>

    <div class="mb-8 bg-amber-50 border-l-4 border-amber-500 rounded-r-2xl p-4 sm:p-6 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="bg-amber-500 text-white p-2 rounded-lg shrink-0 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-amber-900 font-black text-sm sm:text-base uppercase tracking-tight mb-1 italic underline decoration-amber-600/30">কৌশল (Summary Strategy for A+):</h4>
                <ul class="text-amber-800 text-xs sm:text-[13px] leading-relaxed space-y-1 list-disc pl-4 font-medium">
                    <li>সামারি অবশ্যই মূল প্যাসেজের <b>এক-তৃতীয়াংশ (1/3rd)</b> দৈর্ঘ্যের মধ্যে লিখবে।</li>
                    <li>প্যাসেজের কোনো লাইন হুবহু কপি না করে সহজ ইংরেজি ও নিজস্ব ভাষায় মূল ভাবটি প্রকাশ করবে।</li>
                    <li>প্যাসেজে দেওয়া উদাহরণ, উদ্ধৃতি (Quotation) বা অপ্রাসঙ্গিক পরিসংখ্যান এড়িয়ে চলো।</li>
                    <li>প্রথম বাক্যে প্যাসেজের মূল বিষয়বস্তু বা থিম ফুটিয়ে তোলা সবচেয়ে কার্যকর।</li>
                </ul>
            </div>
        </div>
    </div>

    <section class="mb-8 px-1">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-4 leading-snug">
            3. Write a summary of the following text:
        </h2>
        <div class="p-4 sm:p-8 bg-slate-50 border border-slate-200 rounded-2xl italic text-slate-700 leading-relaxed text-sm sm:text-base font-serif shadow-inner relative">
            <div class="absolute -top-3 left-6 bg-white px-3 py-0.5 border border-slate-200 text-[10px] font-bold uppercase text-slate-400 rounded-full shadow-sm">The Passage</div>
            <p class="text-justify">
                "We spend money for different reasons. In fact, spending is a part of our life. Spending may make us happy or unhappy depending on how and why we spend. When we spend money on things that we need and within our limit, it is good. When it becomes a compulsive behaviour, it makes life stressful. Unnecessary spending or spending beyond one's means has some bad effects. For one thing, it may lead to financial ruin or debt, and for another, it may create unhappiness within families. People who overspend are never satisfied with what they have... eventually create psychological problems."
            </p>
        </div>
    </section>

    <section class="bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border-t-4 border-indigo-600">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 px-6 py-4 text-white flex justify-between items-center">
            <h3 class="font-black uppercase tracking-widest text-xs italic">Answer Script for Exam</h3>
        </div>
        
        <div class="p-6 font-mono text-sm text-slate-300">
            <p class="font-bold text-center underline text-white mb-6 italic">Ans to the Q. no-3</p>
            
            <div class="p-4 sm:p-6 bg-white/5 border border-white/10 rounded-2xl relative">
                <h4 class="text-amber-400 font-bold text-center mb-4 uppercase underline underline-offset-4">Summary of the Passage</h4>
                <p class="text-slate-200 leading-loose text-justify text-xs sm:text-sm">
                    Spending is an integral part of human life, but its impact depends on our habits. While spending on necessities within limits brings satisfaction, excessive or impulsive spending leads to severe consequences like financial crisis and domestic unhappiness. Moreover, a constant craving for luxury and brands can turn into a harmful addiction, resulting in psychological distress. Therefore, wise and controlled spending is essential for maintaining overall well-being.
                </p>
            </div>
        </div>
    </section>
</div>
@endsection