<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">বিষয়ভিত্তিক প্রস্তুতি</h2>
                <p class="text-gray-600 mt-2">এক্সপার্টদের দ্বারা যাচাইকৃত সমাধান সহ আপনার পছন্দের বিষয়টি বেছে নিন।</p>
            </div>
            <a href="{{ route('search') }}" class="hidden md:block text-blue-600 font-medium hover:text-blue-700">সবগুলো বিষয় দেখুন →</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-2 md:gap-4">
            {{-- BCS Cluster --}}
            @foreach($bcsSubjects as $subject)
            <a href="{{ route('subject.show', ['subject_slug' => $subject->slug]) }}" 
               class="bcs bg-white p-4 md:p-6 rounded-xl border border-gray-200 hover:border-blue-400 hover:shadow-md transition-all group flex flex-col justify-between min-h-[100px]">
                <h4 class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors leading-tight">
                    {{ $subject->name }}
                </h4>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold group-hover:text-blue-500">BCS PREP</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 group-hover:text-blue-500 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
            @endforeach

            {{-- HSC Cluster --}}
            @foreach($hscSubjects as $subject)
            {{-- Removed 'hidden' class to ensure visibility --}}
            <a href="{{ route('subject.show', ['subject_slug' => $subject->slug]) }}" 
               class="hsc bg-white p-4 md:p-6 rounded-xl border border-gray-200 hover:border-emerald-400 hover:shadow-md transition-all group flex flex-col justify-between min-h-[100px]">
                <h4 class="font-bold text-gray-800 group-hover:text-emerald-600 transition-colors leading-tight">
                    {{ $subject->name }}
                </h4>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold group-hover:text-emerald-500">HSC PREP</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 group-hover:text-emerald-500 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
        
        <div class="mt-8 md:hidden">
            <a href="{{ route('search') }}" class="block text-center py-3 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium active:bg-gray-50">
                সবগুলো বিষয় দেখুন
            </a>
        </div>
    </div>
</section>