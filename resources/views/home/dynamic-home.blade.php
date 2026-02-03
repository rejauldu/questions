<div id="dynamic-home-container" class="flex flex-col gap-14 opacity-0 transition-opacity duration-500">
    
    <section id="section-resume" class="order-first hidden py-6 bg-gradient-to-r from-warning-50 to-white border-y border-warning-100">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-warning-400 rounded-full flex items-center justify-center text-white shadow-lg animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">পড়া চালিয়ে যান...</h3>
                    <p class="text-sm text-slate-600" id="resume-text">আপনার সর্বশেষ পঠিত অধ্যায়টি লোড হচ্ছে</p>
                </div>
            </div>
            <div id="resume-link-container"></div>
        </div>
    </section>

    <section id="section-hsc" class="order-1 py-4 transition-all duration-500">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-extrabold text-slate-900 mb-2">এইচএসসি বোর্ড প্রশ্ন</h2>
                <p class="text-slate-500 font-medium">সকল বোর্ডের সর্বশেষ বছরের প্রশ্ন ও সমাধান</p>
                <div class="w-20 h-1.5 bg-indigo-500 mx-auto rounded-full mt-4"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-2 gap-2 md:gap-4">
                @foreach(['2025', '2024'] as $year)
                {{-- Updated HSC Route with default category and board_id --}}
                <a href="{{ route('hsc.show', ['subject' => 'ict', 'year' => $year, 'category' => 'mcq']) }}" class="group relative p-4 md:p-10 bg-white border border-slate-100 rounded-lg text-center hover:border-indigo-200 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-md">
                        {{ $year == '2025' ? 'Current' : 'Recent' }}
                    </div>
                    <span class="block text-3xl md:text-6xl font-black text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">{{ $year }}</span>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-[0.3em]">বোর্ড সল্যুশন</span>
                </a>
                @endforeach

                <a href="search" class="col-span-2 flex flex-col items-center justify-center p-4 md:p-10 border-2 border-dashed border-slate-200 rounded-lg text-slate-400 font-bold hover:border-indigo-300 hover:text-indigo-500 hover:bg-indigo-50/30 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    বিষয় ভিত্তিক খুঁজুন
                </a>
            </div>
        </div>
    </section>

    <section id="section-bcs" class="order-2 py-4 transition-all duration-500 pt-8 lg:pt-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-extrabold text-slate-900 mb-2">বিসিএস প্রিলিমিনারি</h2>
                <p class="text-slate-500 font-medium">১০ম থেকে ৫০তম বিসিএস এর পূর্ণাঙ্গ সমাধান</p>
                <div class="w-20 h-1.5 bg-primary-500 mx-auto rounded-full mt-4"></div>
            </div>
            
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2 md:gap-4">
                @foreach(range(50, 39) as $no)
                {{-- BCS Route remains the same as it only needs year --}}
                <a href="{{ route('bcs.show', ['year' => $no]) }}" class="group flex flex-col items-center justify-center p-2 lg:p-4 bg-white border border-slate-100 rounded-md md:rounded-lg shadow-sm hover:shadow-xl hover:border-primary-200 transition-all duration-300">
                    <span class="text-xl md:text-4xl font-black text-slate-800 group-hover:text-primary-600 transition-colors">{{ $no }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 group-hover:text-slate-600">তম বিসিএস</span>
                </a>
                @endforeach
                <a href="{{ route('search') }}" class="flex flex-col items-center justify-center p-2 lg:p-4 border-2 border-dashed border-slate-200 rounded-lg text-slate-400 font-bold hover:border-indigo-300 hover:text-indigo-500 hover:bg-indigo-50/30 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    অন্য বিসিএস
                </a>
            </div>
        </div>
    </section>
</div>