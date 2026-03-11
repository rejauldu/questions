@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-3 py-4 sm:py-8 bg-white shadow-sm rounded-xl sm:rounded-2xl border border-slate-100">
    <nav class="text-[10px] sm:text-[11px] font-bold mb-4 uppercase tracking-widest px-1 flex justify-between items-center border-b border-slate-50 pb-2">
        <div class="flex items-center gap-2">
            <span class="text-indigo-600">HSC English 1st</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 9</span>
        </div>
    </nav>
    
    <header class="mb-6 px-1">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mb-1 uppercase italic underline decoration-indigo-500 decoration-4 underline-offset-4">
            Informal Letter Writing
        </h1>
    </header>

    <div class="mb-8 bg-indigo-50 border-l-4 border-indigo-600 rounded-r-2xl p-4 sm:p-5 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="bg-indigo-600 text-white p-2 rounded-lg shrink-0 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-indigo-900 font-black text-sm sm:text-base uppercase tracking-tight mb-1 italic underline decoration-indigo-200">চিঠি লেখার নিয়ম (Letter Format):</h4>
                <ul class="text-indigo-800 text-xs sm:text-[13px] leading-snug space-y-1 list-disc pl-4 font-medium">
                    <li>চিঠির শুরুতে অবশ্যই <b>Date</b> এবং আপনার <b>Address</b> বাম অথবা ডান পাশে লিখবে।</li>
                    <li>বন্ধুর ক্ষেত্রে <b>Salutation</b> হিসেবে 'Dear [Name]' ব্যবহার করা সবচেয়ে উপযোগী।</li>
                    <li>চিঠির শেষে <b>Subscription</b> হিসেবে 'Yours ever' বা 'Your loving friend' ব্যবহার করবে।</li>
                </ul>
            </div>
        </div>
    </div>

    <section class="mb-10 px-1">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-4 leading-snug">
            9. Write an informal letter based on the following task:
        </h2>
        <div class="p-6 bg-slate-900 rounded-3xl relative overflow-hidden shadow-2xl border-b-4 border-indigo-500">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <p class="text-slate-100 text-sm sm:text-lg leading-relaxed italic font-serif">
                "Imagine you are Lisa/Latif. You have a Facebook friend named Smith in Australia who has shown interest in visiting Bangladesh. Now write a letter to him giving a brief account of the sights and sounds of Bangladesh."
            </p>
        </div>
    </section>

    <section class="bg-slate-50 rounded-3xl overflow-hidden border border-slate-200 shadow-xl">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 px-6 py-4 text-white flex justify-between items-center">
            <h3 class="font-black uppercase tracking-widest text-xs italic">Model Answer Script</h3>
            <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded text-indigo-100 uppercase font-bold tracking-tighter">Marks: 10</span>
        </div>
        
        <div class="p-6 sm:p-10 font-serif text-slate-800 leading-[1.8] text-sm sm:text-base">
            <p class="font-bold text-center underline text-indigo-600 mb-8 italic text-sm sm:text-base uppercase tracking-widest">
                Ans to the Q. no-9
            </p>

            <div class="mb-8 text-slate-500 font-sans text-xs sm:text-sm border-l-2 border-indigo-100 pl-4">
                <p>12/A, Dhanmondi</p>
                <p>Dhaka, Bangladesh</p>
                <p>March 01, 2026</p>
            </div>

            <p class="font-bold text-slate-900 mb-6 font-sans">Dear Smith,</p>
            
            <p class="mb-4">
                I was delighted to receive your message. It’s wonderful to hear that you are planning to visit Bangladesh soon! You’ve asked about the sights and sounds of my country, and I can tell you that you are in for a unique experience.
            </p>
            
            <p class="mb-4">
                Bangladesh is a land of lush greenery and majestic rivers. You must visit <b class="text-indigo-700">Cox's Bazar</b>, the world's longest natural sea beach, and the <b class="text-indigo-700">Sundarbans</b>, the largest mangrove forest and home of the Royal Bengal Tiger. If you love hills, the tea gardens of <b class="text-indigo-700">Sylhet</b> will mesmerize you with their serene beauty. 
            </p>

            <p class="mb-4">
                The sounds here are just as vibrant as the sights. From the busy hum of Dhaka city to the melodious <b>'Bhatiali'</b> songs of boatmen in the rural areas, every corner has its own rhythm. Above all, you will love the hospitality of our people; we take great pride in welcoming our guests with open hearts.
            </p>

            <p class="mb-10">
                I am eagerly waiting for your arrival. Let me know your flight details so I can pick you up. 
            </p>

            <div class="mt-10 font-sans">
                <p class="text-slate-500 italic">Yours ever,</p>
                <p class="font-black text-indigo-600 text-lg uppercase tracking-tighter">Latif</p>
            </div>

            <div class="mt-12 max-w-sm border-2 border-slate-300 p-5 rounded-xl bg-white shadow-sm relative overflow-hidden group">
                <div class="absolute top-3 right-3 border-2 border-slate-400 px-3 py-5 text-[9px] font-black text-slate-400 rounded-sm">STAMP</div>
                <div class="font-sans text-[11px] leading-loose">
                    <div class="mb-6">
                        <span class="bg-slate-900 text-white px-1.5 py-0.5 rounded text-[9px] font-bold uppercase mr-1">From</span>
                        <span class="font-bold">Latif, Dhaka, Bangladesh</span>
                    </div>
                    <div>
                        <span class="bg-indigo-600 text-white px-1.5 py-0.5 rounded text-[9px] font-bold uppercase mr-1">To</span>
                        <span class="font-bold">Smith, Sydney, Australia</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection