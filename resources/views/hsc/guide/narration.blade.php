@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 py-4 sm:px-4 sm:py-8 bg-white min-h-screen text-slate-700">
    <div class="mb-6 px-1">
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-indigo-600 mb-1">
            <span>HSC English 2nd</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 5</span>
        </nav>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Narration</h1>
    </div>

    <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 mb-8 rounded-r-xl shadow-sm">
        <h3 class="text-indigo-900 font-black text-sm uppercase mb-1">কৌশল (General Techniques):</h3>
        <ul class="text-indigo-900 text-sm sm:text-base space-y-2 list-disc pl-5 leading-snug">
            <li>Reporting Verb-এর Tense অনুযায়ী Reported Speech-এর Tense পরিবর্তন করো।</li>
            <li>Person (I, You, We) পরিবর্তনের ক্ষেত্রে Speaker এবং Listener-কে অনুসরণ করো।</li>
        </ul>
    </div>

    <section class="mb-10 px-1 space-y-4">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-2">5. Change the narrative style of the following text, by using indirect speeches:</h2>
        <div class="bg-slate-900 rounded-2xl p-6 text-slate-100 shadow-xl italic leading-relaxed text-sm sm:text-lg">
            “Where are you going?” said the merchant. “I was coming to see you.” “What do you want?” “To earn my bread by the labour of my hands.” “Do you really want work?” said the merchant. “Yes, if you have any.” “Then follow me and carry a box from a shop to my house.” “I do not see how I can do that,” said the youth.
        </div>
    </section>

    

    <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <h2 class="mb-4 italic border-b-2 border-indigo-400 pb-1 inline-block text-lg">Ans to the Q. no-5</h2>
        <div class="text-sm sm:text-base leading-loose font-medium mt-2">
            The merchant asked the youth where he was going. The youth replied that he had been going to see him. The merchant asked what he wanted. The youth replied that he wanted to earn his bread by the labour of his hands. The merchant again asked if he really wanted work. The youth replied in the affirmative and said that he really wanted work if he had any. Then the merchant ordered the youth to follow him and carry a box from a shop to his house. The youth said that he did not see how he could do that.
        </div>
    </div>
</div>
@endsection