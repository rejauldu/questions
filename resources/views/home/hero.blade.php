<section class="bg-indigo-700 text-center py-10 sm:py-16 md:py-20 lg:py-24">
    <!-- Changed to flex-col and added space-y-4 here to manage vertical stacking and spacing -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center space-y-4">

        {{-- Tagline (Order 1 on all screens) --}}
        <p class="px-4 py-1 mx-auto bg-white/10 text-yellow-400 rounded-full text-xs 
            font-semibold uppercase tracking-widest border border-yellow-400/50 w-max shadow-md order-1">
            The Ultimate Exam Prep Navigator
        </p>

        {{-- Main Headings (Order 2 on all screens) --}}
        <!-- Removed mt-2, relying on space-y-4 for spacing -->
        <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-white leading-tight order-2">
            Your Exam Dates. Solved Instantly.
        </h1>

        {{-- Search Bar & Primary CTA --}}
        <!-- MOBILE: order-3 (Immediately after H1) | DESKTOP: order-5 (After P) -->
        <!-- Removed mt-6, relying on space-y-4 for spacing -->
        <div class="flex flex-col items-center space-y-6 order-3 md:order-5 w-full max-w-lg">
            
            <!-- Search Bar -->
            <form action="{{ route('search') }}" method="GET" class="flex w-full">
                <div class="flex items-center bg-white rounded-full shadow-2xl w-full
                            transition-all duration-200 p-1 
                            focus-within:ring-4 focus-within:ring-yellow-400/70">

                    <!-- Search Input Field -->
                    <input
                        type="text"
                        name="q"
                        placeholder="Search an Exam Name, University, or Subject..."
                        class="w-full text-gray-800 placeholder-gray-500 text-lg py-2 pl-6
                            border-none outline-none focus:outline-none 
                            focus:ring-0 focus:border-0 p-0 m-0 bg-transparent"
                        value="{{ $search ?? '' }}" />
                    
                    <!-- Search Button -->
                    <button
                        type="submit"
                        class="flex items-center justify-center bg-indigo-600 text-white rounded-full 
                                h-10 w-10 sm:h-12 sm:w-12 flex-shrink-0 transition duration-300 
                                hover:bg-indigo-500 shadow-md transform hover:scale-105"
                        aria-label="Search">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Ask the Bot Button -->
            <a href="{{ url('/chatbot') }}"
                class="inline-flex items-center justify-center bg-yellow-400 text-indigo-800 text-xl font-extrabold 
                        px-10 py-3 rounded-full shadow-lg transition duration-300 
                        hover:bg-yellow-300 hover:shadow-xl transform hover:scale-105 
                        tracking-wider uppercase">
                Ask the Bot
                <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>


        {{-- Sub-heading (MOBILE: order-4 | DESKTOP: order-3) --}}
        <!-- Removed mt-2, relying on space-y-4 for spacing -->
        <h2 class="text-3xl sm:text-4xl font-bold text-yellow-300 order-4 md:order-3">
            Access Questions from Recent Years, Fast.
        </h2>

        {{-- Subtext (MOBILE: order-5 | DESKTOP: order-4) --}}
        <!-- Removed mt-3, relying on space-y-4 for spacing -->
        <p class="text-white/80 max-w-3xl mx-auto text-lg order-5 md:order-4">
            Stop searching countless websites. Use our smart Chatbot to instantly query public and university exam dates, 
            and browse our extensive archive of past years' questions to maximize your preparation.
        </p>

        {{-- Decorative Icons Row (Order 6 on all screens) --}}
        <!-- Adjusted padding/margin to work with parent space-y-4 -->
        <div class="flex justify-center gap-12 mt-4 md:mt-8 order-6">
            <svg class="w-10 h-10 text-yellow-400 opacity-80 animate-pulse" fill="currentColor"
                viewBox="0 0 24 24">
                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02
                    L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z">
                </path>
            </svg>

            <svg class="w-12 h-12 text-yellow-400 opacity-80 animate-pulse delay-300" fill="currentColor"
                viewBox="0 0 24 24">
                <path d="M10 12l2 2l-2 2l-2-2zM18 6l2 2l-2 2l-2-2zM6 6l2 2l-2 2l-2-2z"></path>
            </svg>
        </div>

    </div>
</section>