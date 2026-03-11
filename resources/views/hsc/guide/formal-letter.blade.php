@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 py-4 sm:px-4 sm:py-8 bg-white min-h-screen text-slate-700">
    <div class="mb-6 px-1">
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-indigo-600 mb-1">
            <span>HSC English 2nd</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 10</span>
        </nav>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Formal Letter</h1>
    </div>

    <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 mb-8 rounded-r-xl shadow-sm">
        <h3 class="text-indigo-900 font-black text-sm uppercase mb-1">কৌশল (General Techniques):</h3>
        <ul class="text-indigo-900 text-sm sm:text-base space-y-2 list-disc pl-5 leading-snug">
            <li>অ্যাপ্লিকেশন লেখার সময় সঠিক ফরম্যাট (Date, Address, Subject, Salutation) মেনে চলো।</li>
            <li>বিষয়বস্তু সংক্ষেপে এবং মার্জিত ভাষায় উপস্থাপন করো।</li>
        </ul>
    </div>

    <section class="mb-10 px-1 space-y-4">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-2">10. Suppose you are a student of a college in Dhaka. Many outsiders disturb the students in your college campus during class hours. Now, on behalf of the students, write an application to your Principal for taking measures against the outsiders.</h2>
    </section>

    

    <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <h2 class="mb-4 italic border-b-2 border-indigo-400 pb-1 inline-block text-lg">Ans to the Q. no-10</h2>
        <div class="text-sm sm:text-base leading-relaxed font-medium mt-2 space-y-4">
            <div class="space-y-0.5">
                <p>{{ date('F d, Y') }}</p>
                <p>The Principal</p>
                <p>X College, Dhaka</p>
            </div>

            <p class="font-bold">Subject: Application for taking measures against outsiders in the college campus</p>
            
            <p>Sir</p>
            
            <p class="text-justify">
                We, the students of your college, would like to draw your kind attention to the fact that our academic environment is being seriously hampered by the frequent entrance of many outsiders. These outsiders enter the campus during class hours and create various disturbances. They often engage in loud talking, shouting, and even teasing the students, which makes it difficult for us to concentrate on our lessons. 
            </p>
            
            <p class="text-justify">
                Under these circumstances, we pray and hope that you would be kind enough to take necessary measures, such as strengthening the security at the gate or restricting unauthorized entry, to ensure a peaceful environment for our studies.
            </p>
            
            <div class="pt-2">
                <p>Yours obediently</p>
                <p>The students of X College, Dhaka</p>
            </div>
        </div>
    </div>
</div>
@endsection