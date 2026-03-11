@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 sm:px-3 py-4 sm:py-8 bg-white shadow-sm rounded-xl sm:rounded-2xl border border-slate-100">
    <nav class="text-[10px] sm:text-[11px] font-bold mb-4 uppercase tracking-widest px-1 flex justify-between items-center border-b border-slate-50 pb-2">
        <div class="flex items-center gap-2">
            <span class="text-indigo-600">HSC English 1st</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 8</span>
        </div>
    </nav>
    
    <header class="mb-6 px-1">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mb-1 uppercase italic underline decoration-rose-500 decoration-4 underline-offset-4">
            Completing Story
        </h1>
    </header>

    <div class="mb-8 bg-rose-50 border-l-4 border-rose-500 rounded-r-2xl p-4 sm:p-5 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="bg-rose-500 text-white p-2 rounded-lg shrink-0 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-rose-900 font-black text-sm sm:text-base uppercase tracking-tight mb-1 italic underline decoration-rose-200">গল্প লেখার নিয়ম (Creative Tips):</h4>
                <ul class="text-rose-800 text-xs sm:text-[13px] leading-snug space-y-1 list-disc pl-4 font-medium">
                    <li>গল্পের শুরুতে অবশ্যই একটি সুন্দর এবং প্রাসঙ্গিক <b>Title</b> দিতে হবে।</li>
                    <li>ঘটনার বর্ণনা দেওয়ার সময় <b>Past Tense</b> ব্যবহার করার চেষ্টা করবে।</li>
                    <li>গল্পের শেষে একটি <b>Moral</b> দেওয়া জরুরি, যা গল্পের পূর্ণতা দেয়।</li>
                </ul>
            </div>
        </div>
    </div>

    <section class="mb-10 px-1">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-4 leading-snug">
            8. The following is the beginning of a story. Complete it in your own words and give a suitable title:
        </h2>
        <div class="p-6 bg-slate-900 rounded-3xl relative overflow-hidden shadow-2xl border-b-4 border-rose-500">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H16.017C14.9124 8 14.017 7.10457 14.017 6V3L21.017 3V15C21.017 16.1046 20.1216 17 19.017 17H16.017V21H14.017ZM3.01697 21L3.01697 18C3.01697 16.8954 3.9124 16 5.01697 16H8.01697C8.56925 16 9.01697 15.5523 9.01697 15V9C9.01697 8.44772 8.56925 8 8.01697 8H5.01697C3.9124 8 3.01697 7.10457 3.01697 6V3L10.017 3V15C10.017 16.1046 9.12158 17 8.01697 17H5.01697V21H3.01697Z"></path></svg>
            </div>
            <p class="text-slate-100 text-sm sm:text-lg leading-relaxed italic font-serif">
                "It was a foggy winter morning. Dr. Rabbi was driving home from hospital after his exhausting overnight shift. Driving was difficult because of the haze. Suddenly, he felt he had driven past somebody sitting by the road. He stopped the car and backed up to see who it was. It was an elderly man, dressed in thin, ragged clothes..."
            </p>
        </div>
    </section>

    <section class="bg-slate-50 rounded-3xl overflow-hidden border border-slate-200 shadow-xl">
        <div class="bg-gradient-to-r from-rose-500 to-rose-700 px-6 py-4 text-white flex justify-between items-center">
            <h3 class="font-black uppercase tracking-widest text-xs italic">Model Answer Script</h3>
            <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded text-rose-100 uppercase font-bold tracking-tighter">Marks: 15</span>
        </div>
        
        <div class="p-6 sm:p-10 font-serif text-slate-800 leading-[1.8] text-sm sm:text-base">
            <p class="font-bold text-center underline text-rose-600 mb-8 italic text-sm sm:text-base uppercase tracking-widest">
                Ans to the Q. no-8
            </p>

            <h3 class="text-center font-black text-lg sm:text-xl text-indigo-700 uppercase mb-8 underline decoration-indigo-200 underline-offset-8">
                A Doctor’s Compassion in the Cold
            </h3>
            
            <p class="mb-4">
                The elderly man was shivering violently in the biting cold. His face was pale, and he looked frail. Dr. Rabbi, being a physician, immediately realized the man was suffering from severe hypothermia. Despite his own exhaustion, the doctor didn't hesitate. He got out of his car, wrapped his own warm coat around the old man, and helped him into the passenger seat.
            </p>
            
            <p class="mb-4">
                The man was barely conscious. Dr. Rabbi drove him back to the hospital. On the way, he learned that the man had no family and had been wandering for days in search of food. At the hospital, the doctor personally supervised his treatment and ensured he had a warm bed and nutritious meal. Within a few hours, the man’s condition stabilized. When he opened his eyes, he saw Dr. Rabbi smiling at him. Tears of gratitude rolled down his withered cheeks.
            </p>
            
            <p class="mb-6">
                Dr. Rabbi didn't stop there. He contacted a local shelter home for the elderly and arranged for the man’s permanent stay. As the doctor finally headed home, he no longer felt exhausted. The inner peace he felt from saving a helpless life was far greater than any fatigue. 
            </p>

            <div class="mt-8 p-4 bg-indigo-50 border-2 border-indigo-100 rounded-2xl flex items-center gap-3">
                <div class="bg-indigo-600 text-white p-2 rounded-full shadow-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"></path></svg>
                </div>
                <p class="text-indigo-800 font-bold italic text-sm sm:text-base">
                    Moral: Humanity is the highest form of service.
                </p>
            </div>
        </div>
    </section>
</div>
@endsection