<section class="bg-indigo-800 text-center py-10 sm:py-20 md:py-24 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center space-y-4 sm:space-y-5">

        {{-- Tagline (Order 1 on all screens) --}}
        <p class="px-3 py-1 mx-auto bg-indigo-600 text-white rounded-full text-xs 
            font-medium uppercase tracking-widest w-max shadow-md order-1">
            Exam Preparation Simplified
        </p>

        {{-- Main Headings (Order 2 on all screens) --}}
        <h1 class="text-3xl sm:text-5xl md:text-6xl font-extrabold text-white leading-snug order-2 max-w-4xl">
            Find Your <span class="text-yellow-400">Exam Dates</span> & Questions.
        </h1>

        {{-- Primary Actions Container (Search & Chatbot) --}}
        {{-- MOBILE: order-3 (Immediately after H1) | DESKTOP: order-5 --}}
        <div class="flex flex-col items-center space-y-4 order-3 md:order-5 w-full max-w-xl">
            
            <form action="{{ route('questions.index') }}" method="GET" class="flex w-full">
                <div class="flex items-center bg-white rounded-xl shadow-2xl w-full
                            transition-all duration-300 p-1 
                            focus-within:ring-4 focus-within:ring-yellow-400/70 focus-within:shadow-yellow-400/50"> 

                    <input
                        type="text"
                        name="q"
                        placeholder="Search Exam, University, or Subject..."
                        class="w-full text-gray-800 placeholder-gray-500 text-base sm:text-lg py-2 pl-4 sm:pl-6
                            border-none outline-none focus:outline-none 
                            focus:ring-0 focus:border-0 p-0 m-0 bg-transparent"
                        value="{{ $search ?? '' }}" />
                    
                    <button
                        type="submit"
                        class="flex items-center justify-center bg-indigo-600 text-white rounded-lg 
                                h-10 w-10 sm:h-12 sm:w-12 flex-shrink-0 transition duration-300 
                                hover:bg-indigo-500 shadow-lg transform hover:scale-105"
                        aria-label="Search">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <a href="{{ url('/chatbot') }}"
                class="inline-flex items-center justify-center bg-yellow-400 text-gray-900 text-base font-extrabold 
                        px-6 py-2 rounded-full shadow-lg transition duration-300 
                        hover:bg-yellow-300 hover:shadow-xl transform hover:scale-105 
                        tracking-wide w-full sm:w-auto"> 
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z"></path>
                </svg>
                Ask the Bot
            </a>
        </div>


        {{-- Sub-heading (MOBILE: order-4 | DESKTOP: order-3) --}}
        <h2 class="text-lg sm:text-2xl font-semibold text-indigo-200 order-4 md:order-3 pt-2 sm:pt-0">
            Access recent years' papers and schedule information instantly.
        </h2>

        {{-- Subtext (Order 6 on all screens) --}}
        <p class="text-indigo-200/60 max-w-3xl mx-auto text-sm order-6 md:order-6 pt-2">
            * We use public data and university archives. Check official sources for final confirmation.
        </p>

    </div>
</section>