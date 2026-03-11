@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-3 py-4 sm:py-8 bg-white shadow-sm rounded-xl sm:rounded-2xl border border-slate-100">
    <nav class="text-[10px] sm:text-[11px] font-bold mb-2 sm:mb-4 uppercase tracking-widest px-1 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <span class="text-indigo-600">HSC English 1st</span>
            <span class="text-slate-300">/</span>
            <span class="text-gray-400">1(B)</span>
        </div>
    </nav>
    
    <header class="mb-4 sm:mb-8 px-1">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 leading-tight mb-1 uppercase italic underline decoration-indigo-500 decoration-4 underline-offset-4">
            Short Answer Questions
        </h1>
    </header>

    <div class="mb-6 sm:mb-8 bg-amber-50 border-l-4 border-amber-400 rounded-r-xl p-4 sm:p-6 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="bg-amber-500 text-white p-1.5 rounded-lg shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-amber-900 font-black text-sm sm:text-base uppercase tracking-wide mb-1 italic underline underline-offset-2">কৌশল ও পরামর্শ (Guidelines)</h4>
                <ul class="text-amber-800 text-xs sm:text-[13px] leading-relaxed list-disc list-inside space-y-1 font-medium">
                    <li>প্রশ্নের উত্তর সরাসরি প্যাসেজ থেকে কপি না করে নিজের ভাষায় <b>Paraphrasing</b> করো।</li>
                    <li>উত্তর খুব বেশি বড় করার প্রয়োজন নেই; ২ থেকে ৩টি পূর্ণাঙ্গ বাক্যে টু-দ্য-পয়েন্ট উত্তর দাও।</li>
                    <li>প্রশ্ন যে <b>Tense</b>-এ আছে, উত্তরটিও সেই একই Tense-এ লিখতে হবে।</li>
                </ul>
            </div>
        </div>
    </div>

    <h2 class="text-slate-900 mb-4 inline-block font-bold text-sm sm:text-base uppercase tracking-tight">1. Read the passage and answer the questions B:</h2>

    <div class="mb-6 sm:mb-10 p-4 sm:p-8 bg-slate-50 border-l-4 border-indigo-500 rounded-r-xl shadow-inner italic text-gray-700 leading-relaxed text-sm sm:text-base font-serif">
        <p class="mb-4">
            ...He was a free-will agent and he chose to do careful work, and if he failed, he took the responsibility without subterfuge. And he did for me the unnecessary thing, the gracious thing, that we find done only by the great of heart.
        </p>
        <p>
            ...I found that when I tried to return his thoughtfulness with such things as candy and apples, he was wordless. 'Thank you' was perhaps an expression for which he had had no use, for his courtesy was instinctive.
        </p>
    </div>

    <section class="space-y-4 mb-10">
        @php
            $questions = [
                'What unnecessary things did Jerry do for the authoress?',
                'How would Jerry usually react after getting gifts from the authoress?',
                'Why did Jerry refuse to take money from the authoress to repair the ax handle?',
                'Why was the authoress impressed with Jerry?',
                'Do you like the personality of Jerry? Why?'
            ];
        @endphp

        <div class="grid gap-3 sm:gap-4">
            @foreach($questions as $index => $question)
            <div class="p-3 sm:p-4 border border-gray-100 rounded-xl bg-slate-50 shadow-sm flex gap-3 items-start">
                <span class="bg-indigo-600 text-white font-black text-[10px] px-2 py-1 rounded-md uppercase shrink-0 italic">
                    {{ chr(97 + $index) }}
                </span>
                <p class="text-slate-800 font-bold text-sm sm:text-[15px] leading-tight uppercase tracking-tight">
                    {{ $question }}
                </p>
            </div>
            @endforeach
        </div>
    </section>

    <section class="mt-8 sm:mt-12 bg-slate-900 rounded-xl overflow-hidden shadow-2xl border-t-4 border-indigo-600">
        <div class="bg-indigo-600 px-4 py-2 text-white flex justify-between items-center">
            <h3 class="font-black uppercase tracking-widest text-[10px] sm:text-xs">Answer Script</h3>
            <span class="text-[9px] bg-indigo-500/50 px-2 py-0.5 rounded italic">HSC Standard</span>
        </div>
        <div class="p-4 sm:p-8 font-mono text-xs sm:text-sm text-slate-300">
            <p class="font-bold text-center underline text-white mb-6 italic uppercase tracking-widest">Ans to the Q. no-1(B)</p>
            
            <div class="space-y-8">
                @php
                    $answers = [
                        'Jerry performed several kind acts for the authoress that were not required of him, such as finding a cubbyhole to store dry kindling and fixing a loose stone on her walkway.',
                        'When Jerry received gifts, he usually remained silent or wordless. His gratitude was expressed through the deep, clear look in his eyes rather than through verbal thanks.',
                        'Jerry refused the money because he felt responsible for the accident. He believed the handle broke because he was careless, and his integrity led him to take full accountability.',
                        'The authoress was impressed by Jerry’s extraordinary integrity, his sense of responsibility, and his instinctive courtesy which went beyond mere training.',
                        'Yes, I like Jerry’s personality because of his rare honesty and "great heart." His willingness to admit mistakes and do extra work without being asked makes him a truly noble character.'
                    ];
                @endphp

                @foreach($answers as $index => $answer)
                <div class="flex gap-4 items-start group">
                    <span class="text-amber-400 font-bold uppercase shrink-0 italic text-sm">({{ chr(97 + $index) }})</span>
                    <p class="text-slate-200 leading-relaxed text-justify border-l border-white/10 pl-4 group-hover:border-indigo-500 transition-colors">
                        {{ $answer }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection