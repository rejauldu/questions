@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-3 py-4 sm:py-8 bg-white shadow-sm rounded-xl sm:rounded-2xl border border-slate-100">
    <nav class="text-[10px] sm:text-[11px] font-bold mb-2 sm:mb-4 uppercase tracking-widest px-1 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <span class="text-indigo-600">HSC English 1st</span>
            <span class="text-slate-300">/</span>
            <span class="text-gray-400">1(A)</span>
        </div>
    </nav>
    
    <header class="mb-4 sm:mb-8 px-1">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 leading-tight mb-1 uppercase italic underline decoration-indigo-500 decoration-4 underline-offset-4">
            Multiple Choice Questions
        </h1>
    </header>

    <div class="mb-6 sm:mb-8 bg-indigo-50 border-l-4 border-indigo-600 rounded-r-xl p-4 sm:p-6 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="bg-indigo-600 text-white p-1.5 rounded-lg shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.364-5.364l-.707-.707m12.728 12.727l-.707-.707M6.364 18.364l-.707-.707M12 21v1m0-5a5 5 0 110-10 5 5 0 010 10z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-indigo-900 font-black text-sm sm:text-base uppercase tracking-wide mb-1 italic underline underline-offset-2">কৌশল ও পরামর্শ (Guidelines)</h4>
                <ul class="text-indigo-800 text-xs sm:text-[13px] leading-relaxed list-disc list-inside space-y-1">
                    <li>প্যাসেজটি দ্রুত একবার পড়ে মূল থিমটি বুঝে নাও।</li>
                    <li>সরাসরি শাব্দিক অর্থ না খুঁজে <b>Contextual Meaning</b> বোঝার চেষ্টা করো।</li>
                    <li>Synonym এবং Antonym এর ক্ষেত্রে মূল শব্দের পার্টস অফ স্পিচ খেয়াল রাখা জরুরি।</li>
                </ul>
            </div>
        </div>
    </div>

    <h2 class="text-slate-900 mb-4 inline-block font-bold text-sm sm:text-base uppercase tracking-tight">1. Read the passage and answer the questions A:</h2>
    
    <div class="mb-6 sm:mb-10 p-4 sm:p-8 bg-slate-50 border-l-4 border-indigo-500 rounded-r-xl shadow-inner italic text-gray-700 leading-relaxed text-sm sm:text-base font-serif">
        <p class="mb-4">
            At daylight, I was half-awakened by the sound of chopping. Again it was so even in texture that I went back to sleep. When I left my bed in the cool morning, the boy had come and gone, and a stack of kindling was neat against the cabin wall. He came after school in the afternoon and worked until time to return to the orphanage. His name was Jerry ..... 
        </p>
        <p>
            ...It is bedded on courage, but it is more than brave. It is honest, but it is more than honesty. Jerry said the woodshop at the orphanage would repair it. I brought money to pay for the job and he refused it. "I'll pay for it," he said. "I broke it. I brought the ax down careless."
        </p>
    </div>

    <section class="space-y-4 sm:space-y-6 mb-10">
        <div class="grid gap-4 sm:gap-6">
            @php
                $questions = [
                    ['q' => '(a) Jerry\'s courtesy was ——.', 'opts' => ['artificial', 'acquired', 'inborn', 'taught'], 'ans' => 'iii'],
                    ['q' => '(b) What does the phrase \'of his own accord\' indicate?', 'opts' => ['Half-heartedly', 'Reluctantly', 'Neutrally', 'Willingly'], 'ans' => 'iv'],
                    ['q' => '(c) What does the word \'subterfuge\' in the passage refer to?', 'opts' => ['Deception', 'Honesty', 'Openness', 'Candidness'], 'ans' => 'i'],
                    ['q' => '(d) Jerry wanted to get the ax handle ——.', 'opts' => ['repair', 'fixed', 'made', 'reshaped'], 'ans' => 'ii'],
                    ['q' => '(e) Jerry chose to do careful work because of his ——.', 'opts' => ['intelligence', 'courage', 'meanness', 'responsibility'], 'ans' => 'iv'],
                    ['q' => '(f) The author was half-awakened by the ——.', 'opts' => ['sound of barking', 'sound of footsteps', 'sound of cutting wood', 'sound of rain'], 'ans' => 'iii'],
                    ['q' => '(g) \'Cubbyhole\' means ——.', 'opts' => ['a small enclosed space', 'a noisy place', 'a deep hole', 'a snake hole'], 'ans' => 'i'],
                    ['q' => '(h) \'I saw deep into the clear well of his eyes\'—What did the writer see?', 'opts' => ['Steadiness', 'Gratefulness', 'Farsightedness', 'Freedom'], 'ans' => 'ii'],
                    ['q' => '(i) What does the word \'integrity\' in the passage mean?', 'opts' => ['Intuition', 'Simplicity', 'Uprightness', 'Incompleteness'], 'ans' => 'iii'],
                    ['q' => '(j) Jerry had been at the orphanage ——.', 'opts' => ['for four years', 'for eight years', 'for twelve years', 'for six years'], 'ans' => 'ii'],
                ];
            @endphp

            @foreach($questions as $index => $item)
            <div class="p-3 sm:p-5 border border-gray-100 rounded-xl bg-slate-50 shadow-sm hover:shadow-md transition-shadow">
                <p class="font-bold text-sm sm:text-base text-gray-800 mb-3 leading-tight uppercase tracking-tight">
                    <span class="text-indigo-600 mr-1">Q.</span>{{ $item['q'] }}
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                    @foreach($item['opts'] as $oIndex => $opt)
                        @php $key = ['i','ii','iii','iv'][$oIndex]; @endphp
                        <div class="p-2 sm:p-3 bg-white border rounded-lg text-xs sm:text-sm flex justify-between items-center {{ $key == $item['ans'] ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200' }}">
                            <span class="{{ $key == $item['ans'] ? 'text-emerald-700 font-bold' : 'text-gray-600' }}">
                                ({{ $key }}) {{ $opt }}
                            </span>
                            @if($key == $item['ans'])
                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <section class="mt-8 sm:mt-12 bg-slate-900 rounded-xl overflow-hidden shadow-2xl border-t-4 border-indigo-600">
        <div class="bg-indigo-600 px-4 py-2 text-white flex justify-between items-center">
            <h3 class="font-black uppercase tracking-widest text-[10px] sm:text-xs">Answer Script</h3>
        </div>
        <div class="p-4 font-mono text-xs sm:text-sm text-slate-300">
            <p class="font-bold text-center underline text-white mb-3 italic">Ans to the Q. no-1(A)</p>
            <div class="grid grid-cols-3 sm:grid-cols-5 gap-y-2 text-center">
                <p>(a) iii</p> <p>(b) iv</p> <p>(c) i</p> <p>(d) ii</p> <p>(e) iv</p>
                <p>(f) iii</p> <p>(g) i</p> <p>(h) ii</p> <p>(i) iii</p> <p>(j) ii</p>
            </div>
        </div>
    </section>
</div>
@endsection