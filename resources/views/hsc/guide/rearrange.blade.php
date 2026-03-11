@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-3 py-4 sm:py-8 bg-white shadow-sm rounded-xl sm:rounded-2xl border border-slate-100">
    <nav class="text-[10px] sm:text-[11px] font-bold mb-4 uppercase tracking-widest px-1 flex justify-between items-center border-b border-slate-50 pb-2">
        <div class="flex items-center gap-2">
            <span class="text-indigo-600">HSC English 1st</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 6</span>
        </div>
        <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-[9px] shadow-sm uppercase italic">Curated by BCS Cadre Officers</span>
    </nav>
    
    <header class="mb-6 px-1">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mb-1 uppercase italic underline decoration-indigo-500 decoration-4 underline-offset-4">
            Rearrange
        </h1>
    </header>

    <div class="mb-8 bg-indigo-50 border-l-4 border-indigo-600 rounded-r-2xl p-4 sm:p-5 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="bg-indigo-600 text-white p-2 rounded-lg shrink-0 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-indigo-900 font-black text-sm sm:text-base uppercase tracking-tight mb-1 italic underline decoration-indigo-200">কৌশল (Active Tip):</h4>
                <ul class="text-indigo-800 text-xs sm:text-[13px] leading-snug space-y-1 list-disc pl-4 font-medium">
                    <li>প্রথমে সবগুলো বাক্য পড়ে গল্পের মূল থিম বা ঘটনার ক্রম বোঝার চেষ্টা করো।</li>
                    <li>ঘটনার সময়কাল (যেমন: Night fell, This time, Last time) খেয়াল করলে সিকোয়েন্স মেলানো সহজ হয়।</li>
                </ul>
            </div>
        </div>
    </div>

    <section class="mb-10 px-1">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-4 leading-snug">
            6. Put the following parts of the story in correct order:
        </h2>
        <div class="space-y-2.5">
            @php
                $sentences = [
                    'a' => 'As Saadi was dressed in his usual inexpensive attire, the courtier didn\'t recognize him.',
                    'b' => 'On his way back home from the emperor\'s palace, Saadi deliberately stopped at the same courtier\'s house.',
                    'c' => 'During the meal, however, the guest began to put the delicacies into the folds of his fine clothes.',
                    'd' => 'Sheikh Saadi, a renowned Persian poet, was travelling to the emperor\'s palace.',
                    'e' => 'Saadi said that his clothes, in fact, deserved the food, reminding the host that last time he was shown poor hospitality because of his ordinary clothes.',
                    'f' => 'Seeing the expensive clothes of the guest, the courtier and his men were extremely hospitable, offering him a spread of fine dishes.',
                    'g' => 'Surprised, the courtier asked, "Why are you feeding your clothes, my honorable guest?"',
                    'h' => 'Night fell on the way, and the poet took shelter in a courtier\'s house.',
                    'i' => 'This time, however, he was wearing the luxurious clothes given to him by the emperor.',
                    'j' => 'So, he treated the great man with indifference, offering him only a small meal and poor accommodation.'
                ];
            @endphp

            @foreach($sentences as $key => $text)
                <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm sm:text-[15px] text-slate-700 flex gap-3 shadow-sm hover:shadow-md hover:bg-white hover:border-indigo-100 transition-all group">
                    <span class="font-black text-indigo-400 group-hover:text-indigo-600 transition-colors">({{ $key }})</span>
                    <p class="leading-relaxed">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-12 px-1">
        <h2 class="text-sm font-black text-slate-400 mb-4 uppercase tracking-widest italic">Draft Your Sequence:</h2>
        <div class="overflow-x-auto rounded-xl border-2 border-slate-900 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
            <table class="w-full border-collapse text-center font-bold">
                <thead>
                    <tr class="bg-slate-900 text-white text-[10px] sm:text-xs">
                        @for($i=1; $i<=10; $i++) <th class="border-r border-slate-700 p-2">{{ $i }}</th> @endfor
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white">
                        @for($i=1; $i<=10; $i++) 
                            <td class="border-r-2 border-slate-900 p-0 last:border-r-0">
                                <input type="text" class="w-full h-10 sm:h-12 text-center text-indigo-600 font-black focus:bg-indigo-50 outline-none uppercase text-sm sm:text-base" maxlength="1" placeholder="-">
                            </td> 
                        @endfor
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border-t-4 border-indigo-600">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 px-6 py-4 text-white flex justify-between items-center">
            <h3 class="font-black uppercase tracking-widest text-xs italic">Answer Script for Exam</h3>
            <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded text-indigo-100 uppercase font-bold">Marks: 1 × 8 = 8</span>
        </div>
        
        <div class="p-6 font-mono text-slate-300">
            <p class="font-bold text-center underline text-white mb-8 italic text-sm">Ans to the Q. no-6</p>
            
            <div class="max-w-2xl mx-auto overflow-hidden rounded-xl border border-indigo-400/30 shadow-lg">
                <div class="grid grid-cols-5 sm:grid-cols-10 text-center bg-indigo-950/50">
                    @php $seq = ['1','2','3','4','5','6','7','8','9','10']; @endphp
                    @foreach($seq as $s)
                        <div class="p-2 border-r border-b border-indigo-400/30 text-[10px] text-indigo-300 font-bold bg-indigo-900/40">{{ $s }}</div>
                    @endforeach
                    
                    @php $ans = ['d','h','a','j','b','i','f','c','g','e']; @endphp
                    @foreach($ans as $a)
                        <div class="p-3 border-r border-indigo-400/30 text-amber-400 font-black uppercase text-sm sm:text-base last:border-r-0">{{ $a }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection