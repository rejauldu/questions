@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-3 py-4 sm:py-8 bg-white shadow-sm rounded-xl sm:rounded-2xl border border-slate-100">
    <nav class="text-[10px] sm:text-[11px] font-bold mb-2 sm:mb-4 uppercase tracking-widest px-1 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <span class="text-indigo-600">HSC English 1st</span>
            <span class="text-slate-300">/</span>
            <span class="text-gray-400">Question 4</span>
        </div>
    </nav>
    
    <header class="mb-4 sm:mb-8 px-1">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 leading-tight mb-1 uppercase italic underline decoration-indigo-500 decoration-4 underline-offset-4">
            Cloze Test with Clues
        </h1>
    </header>

    <div class="mb-6 sm:mb-8 bg-indigo-50 border-l-4 border-indigo-600 rounded-r-xl p-4 sm:p-6 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="bg-indigo-600 text-white p-1.5 rounded-lg shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-indigo-900 font-black text-sm sm:text-base uppercase tracking-wide mb-1 italic underline underline-offset-2">কৌশল: যেভাবে সমাধান করবে</h4>
                <ul class="text-indigo-800 text-xs sm:text-[13px] leading-relaxed list-disc list-inside space-y-1 font-medium">
                    <li>প্রথমে বক্সের শব্দগুলোর অর্থ এবং <b>Parts of Speech</b> বুঝে নাও।</li>
                    <li>শূন্যস্থানের আগে ও পরের শব্দ দেখে গ্রামাটিক্যাল ক্লু (যেমন: Article এর পর Noun) খোঁজো।</li>
                    <li>প্রয়োজন অনুযায়ী বক্সের শব্দের রূপ পরিবর্তন (যেমন: Verb থেকে Noun) করতে হতে পারে।</li>
                </ul>
                <p class="text-indigo-700 text-[10px] mt-2 font-bold uppercase tracking-wider italic bg-white/50 inline-block px-2 py-0.5 rounded shadow-sm">Marks: $0.5 \times 10 = 5$</p>
            </div>
        </div>
    </div>

    <section class="mb-10 px-1">
        <h2 class="text-slate-900 mb-4 font-bold text-sm sm:text-base uppercase tracking-tight">4. Fill in the blanks with clues from the box:</h2>
        
        <div class="mb-6 p-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl grid grid-cols-3 sm:grid-cols-4 gap-2 text-center shadow-inner">
            @php
                $clues = ['provide', 'preach', 'constant', 'aware', 'with', 'necessity', 'acquisition', 'of', 'for', 'enrich', 'at', 'dignity'];
            @endphp
            @foreach($clues as $word)
                <span class="bg-white px-2 py-1.5 border border-slate-200 rounded-lg text-xs sm:text-sm font-bold text-indigo-700 shadow-sm hover:border-indigo-300 transition-colors">
                    {{ $word }}
                </span>
            @endforeach
        </div>

        <div class="p-5 sm:p-8 bg-white border border-slate-100 rounded-2xl shadow-sm leading-[2.5] text-slate-800 text-sm sm:text-base font-serif text-justify">
            Education (a) <span class="border-b-2 border-indigo-400 px-4 italic font-bold text-indigo-600">......</span> us with knowledge and (b) <span class="border-b-2 border-indigo-400 px-4 italic font-bold text-indigo-600">......</span> our mind. It also makes us (c) <span class="border-b-2 border-indigo-400 px-4 italic font-bold text-indigo-600">......</span> of our rights and (d) <span class="border-b-2 border-indigo-400 px-4 italic font-bold text-indigo-600">......</span>. It is a (e) <span class="border-b-2 border-indigo-400 px-4 italic font-bold text-indigo-600">......</span> for any kind of development. The (f) <span class="border-b-2 border-indigo-400 px-4 italic font-bold text-indigo-600">......</span> of knowledge is not possible (g) <span class="border-b-2 border-indigo-400 px-4 italic font-bold text-indigo-600">......</span> education. It is (h) <span class="border-b-2 border-indigo-400 px-4 italic font-bold text-indigo-600">......</span> compared to light which removes the darkness (i) <span class="border-b-2 border-indigo-400 px-4 italic font-bold text-indigo-600">......</span> ignorance. So, we must put emphasis (j) <span class="border-b-2 border-indigo-400 px-4 italic font-bold text-indigo-600">......</span> receiving quality education.
        </div>
    </section>

    <section class="bg-slate-900 rounded-xl overflow-hidden shadow-2xl border-t-4 border-indigo-600">
        <div class="bg-indigo-600 px-4 py-2 text-white flex justify-between items-center">
            <h3 class="font-black uppercase tracking-widest text-[10px] sm:text-xs">Answer Script</h3>
            <span class="text-[9px] bg-white/20 px-2 py-0.5 rounded font-bold italic">Standard Format</span>
        </div>
        <div class="p-6 font-mono text-xs sm:text-sm text-slate-300">
            <p class="font-bold text-center underline text-white mb-6 italic tracking-widest uppercase">Ans to the Q. no-4</p>
            
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 text-center">
                @php
                    $answers = [
                        'a' => 'provides',
                        'b' => 'enriches',
                        'c' => 'aware',
                        'd' => 'responsibilities',
                        'e' => 'necessity',
                        'f' => 'acquisition',
                        'g' => 'without',
                        'h' => 'constantly',
                        'i' => 'of',
                        'j' => 'on'
                    ];
                @endphp
                @foreach($answers as $key => $value)
                    <div class="flex flex-col bg-white/5 p-2 rounded border border-white/10 group hover:border-indigo-500 transition-colors">
                        <span class="text-[10px] text-indigo-400 font-bold uppercase">({{ $key }})</span>
                        <span class="text-white font-bold">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6 p-3 bg-white/5 rounded-lg border-l-2 border-amber-500">
                <p class="text-[10px] sm:text-xs text-slate-400 leading-relaxed italic">
                    <strong class="text-amber-500 uppercase not-italic">Note:</strong> খেয়াল করো, (a) এবং (b) তে Subject 'Education' (Third person singular) হওয়ায় verb-এর সাথে <b>s/es</b> যোগ করা হয়েছে। (h) নং এ verb-কে বর্ণনা করতে <b>Adverb</b> (constantly) ব্যবহার করা হয়েছে।
                </p>
            </div>
        </div>
    </section>
</div>
@endsection