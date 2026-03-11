@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-3 py-4 sm:py-8 bg-white shadow-sm rounded-xl sm:rounded-2xl border border-slate-100">
    <nav class="text-[10px] sm:text-[11px] font-bold mb-4 uppercase tracking-widest px-1 flex justify-between items-center border-b border-slate-50 pb-2">
        <div class="flex items-center gap-2">
            <span class="text-indigo-600">HSC English 1st</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 7</span>
        </div>
    </nav>
    
    <header class="mb-6 px-1">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mb-1 uppercase italic underline decoration-amber-500 decoration-4 underline-offset-4">
            Graph & Chart Analysis
        </h1>
    </header>

    <div class="mb-8 bg-amber-50 border-l-4 border-amber-500 rounded-r-2xl p-4 sm:p-5 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="bg-amber-500 text-white p-2 rounded-lg shrink-0 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-amber-900 font-black text-sm sm:text-base uppercase tracking-tight mb-1 italic underline decoration-amber-200">কৌশল (Writing Strategy):</h4>
                <ul class="text-amber-800 text-xs sm:text-[13px] leading-snug space-y-1 list-disc pl-4 font-medium">
                    <li>গ্রাফের তথ্য দেওয়ার সময় নিজের কোনো ব্যক্তিগত মতামত বা 'I think' লিখবে না।</li>
                    <li>সবচেয়ে বেশি (Highest) এবং সবচেয়ে কম (Lowest) শতাংশের মধ্যে তুলনা করা জরুরি।</li>
                    <li>ট্রানজিশনাল ওয়ার্ড যেমন: <b>'In contrast', 'Similarly', 'On the other hand'</b> ব্যবহার করো।</li>
                </ul>
            </div>
        </div>
    </div>

    <section class="mb-10 px-1">
        <div class="bg-slate-50 p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-inner">
            <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-6 leading-relaxed">
                7. The graph below shows the percentage of different types of books preserved in a college library. Describe the graph in at least 150 words. You should highlight and summarize the information given in the graph.
            </h2>
            
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <svg width="100%" viewBox="0 0 600 380" xmlns="http://www.w3.org/2000/svg" class="mx-auto">
                    @for($i=0; $i<=6; $i++)
                        <line x1="60" y1="{{ 320 - ($i * 50) }}" x2="550" y2="{{ 320 - ($i * 50) }}" stroke="#f1f5f9" stroke-width="1"/>
                        <text x="50" y="{{ 325 - ($i * 50) }}" font-family="sans-serif" font-size="12" fill="#94a3b8" text-anchor="end">{{ $i * 5 }}%</text>
                    @endfor

                    <line x1="60" y1="20" x2="60" y2="320" stroke="#475569" stroke-width="2"/>
                    <line x1="60" y1="320" x2="550" y2="320" stroke="#475569" stroke-width="2"/>

                    <rect x="85" y="70" width="50" height="250" fill="#6366f1" rx="4" class="opacity-80"/>
                    <text x="110" y="60" font-family="sans-serif" font-size="12" font-weight="bold" fill="#6366f1" text-anchor="middle">25%</text>
                    
                    <rect x="165" y="20" width="50" height="300" fill="#4f46e5" rx="4"/>
                    <text x="190" y="10" font-family="sans-serif" font-size="12" font-weight="bold" fill="#4f46e5" text-anchor="middle">30%</text>
                    
                    <rect x="245" y="170" width="50" height="150" fill="#818cf8" rx="4" class="opacity-70"/>
                    <text x="270" y="160" font-family="sans-serif" font-size="12" font-weight="bold" fill="#818cf8" text-anchor="middle">15%</text>
                    
                    <rect x="325" y="120" width="50" height="200" fill="#6366f1" rx="4" class="opacity-90"/>
                    <text x="350" y="110" font-family="sans-serif" font-size="12" font-weight="bold" fill="#6366f1" text-anchor="middle">20%</text>
                    
                    <rect x="405" y="270" width="50" height="50" fill="#c7d2fe" rx="4"/>
                    <text x="430" y="260" font-family="sans-serif" font-size="12" font-weight="bold" fill="#94a3b8" text-anchor="middle">5%</text>
                    
                    <rect x="485" y="270" width="50" height="50" fill="#c7d2fe" rx="4"/>
                    <text x="510" y="260" font-family="sans-serif" font-size="12" font-weight="bold" fill="#94a3b8" text-anchor="middle">5%</text>

                    <g font-family="sans-serif" font-size="10" font-weight="600" fill="#64748b" text-anchor="middle">
                        <text x="110" y="340">Literature</text>
                        <text x="190" y="340">Science</text>
                        <text x="270" y="340">History &</text>
                        <text x="270" y="352">Phil.</text>
                        <text x="350" y="340">Business</text>
                        <text x="430" y="340">Sports</text>
                        <text x="510" y="340">Others</text>
                    </g>
                </svg>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border-t-4 border-amber-500">
        <div class="bg-gradient-to-r from-amber-500 to-amber-700 px-6 py-4 text-white flex justify-between items-center">
            <h3 class="font-black uppercase tracking-widest text-xs italic">Model Answer Script</h3>
            <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded text-amber-100 uppercase font-bold tracking-tighter">Word Count: ~160</span>
        </div>
        
        <div class="p-6 sm:p-10 font-serif leading-[1.8] text-slate-300 text-sm sm:text-base text-justify italic">
            <p class="font-bold text-center underline text-amber-400 mb-8 italic text-sm sm:text-base uppercase tracking-widest">
                Ans to the Q. no-7
            </p>

            <p class="mb-4">
                The provided bar graph illustrates the distribution of various categories of books preserved in a college library. The data represents six distinct categories: Literature, Science, History & Philosophy, Business, Sports, and others. 
            </p>

            <p class="mb-4 underline decoration-amber-500/30 underline-offset-8">
                According to the graph, the highest percentage of books belongs to the <b class="text-amber-400 not-italic">Science</b> category, accounting for 30% of the total collection. This is followed by <b class="text-indigo-400 not-italic">Literature</b>, which holds a significant share of 25%. On the other hand, Business-related books occupy 20%, while History and Philosophy books make up 15% of the library's resources. Interestingly, the library has a very limited collection in the <b class="text-slate-400 not-italic">Sports</b> category and other miscellaneous books, each representing only 5% of the total.
            </p>

            <p>
                In summary, the graph shows that the library prioritizes academic and scientific subjects over extracurricular ones. The dominance of Science and Literature suggests that students are more inclined toward these fields, whereas Sports and other categories remain secondary in the library's preservation plan.
            </p>
        </div>
    </section>
</div>
@endsection