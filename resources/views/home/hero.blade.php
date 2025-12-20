<section class="bg-primary-800 text-center py-10 sm:py-20 md:py-24 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center space-y-4 sm:space-y-5">

        {{-- Tagline (Order 1 on all screens) --}}
        <p class="px-3 py-1 mx-auto bg-primary-600 text-white rounded-full text-xs 
            font-medium uppercase tracking-widest w-max shadow-md order-1 hidden sm:block">
            Past Questions, Schedules & AI Guidance
        </p>

        {{-- Main Headings (Order 2 on all screens) --}}
        <h1 class="text-3xl sm:text-5xl md:text-6xl font-extrabold text-white leading-snug order-2 max-w-4xl">
            The Easiest Way to Master <span class="text-warning-400">Past Questions</span> & Schedules.
        </h1>

        {{-- Sub-heading (MOBILE: order-4 | DESKTOP: order-3) --}}
        <h2 class="text-lg sm:text-2xl font-semibold text-primary-200 order-4 md:order-3 pt-2 sm:pt-0 hidden md:block">
            Use the box below to quickly **search by Keyword** or explore all your options below.
        </h2>
        
        {{-- Primary Actions Container (Text Search Input) --}}
        <div class="flex flex-col items-center space-y-4 order-3 md:order-5 w-full max-w-xl">
            
            {{-- Text Input Search (Standard Search) --}}
            <form action="{{ route('questions.index') }}" method="GET" class="flex w-full">
                <div class="flex items-center bg-white rounded-xl shadow-2xl w-full
                            transition-all duration-300 p-1 
                            focus-within:ring-4 focus-within:ring-warning-400/70 focus-within:shadow-warning-400/50"> 

                    <input
                        type="text"
                        name="q"
                        placeholder="E.g., 'Thermodynamics Final 2022' or 'Electrical Engineering'"
                        class="w-full text-secondary-800 placeholder-secondary-500 text-base sm:text-lg py-2 pl-4 sm:pl-6
                            border-none outline-none focus:outline-none 
                            focus:ring-0 focus:border-0 p-0 m-0 bg-transparent"
                        value="{{ $search ?? '' }}" />
                    
                    <button
                        type="submit"
                        class="flex items-center justify-center bg-primary-600 text-white rounded-lg 
                                h-10 w-10 sm:h-12 sm:w-12 flex-shrink-0 transition duration-300 
                                hover:bg-primary-500 shadow-lg transform hover:scale-105"
                        aria-label="Search">
                        <x-icons.search class="w-6 h-6"/>
                    </button>
                </div>
            </form>

            {{-- New: Alternative Search/Action Links --}}
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 w-full justify-center">

                {{-- Link to Dropdown Filter Search Page --}}
                <a href="{{ route('search') }}" 
                    class="inline-flex items-center justify-center bg-primary-500 text-white text-sm font-bold 
                            px-4 py-2 rounded-full shadow-lg transition duration-300 
                            hover:bg-primary-400 hover:shadow-xl transform hover:scale-[1.03] w-full sm:w-auto">
                    <x-icons.funnel/>
                    Use Dropdown Filters
                </a>

                {{-- Link to Chatbot Page --}}
                <a href="{{ url('/chatbot') }}"
                    class="inline-flex items-center justify-center bg-warning-400 text-secondary-900 text-sm font-extrabold 
                            px-4 py-2 rounded-full shadow-lg transition duration-300 
                            hover:bg-warning-300 hover:shadow-xl transform hover:scale-[1.03] 
                            tracking-wide w-full sm:w-auto"> 
                    <x-icons.chatbot/>
                    Ask the <strong>AI Chatbot</strong>
                </a>
            </div>
            
        </div>


        {{-- Subtext (Order 6 on all screens) --}}
        <p class="text-primary-200/60 max-w-3xl mx-auto text-sm order-6 md:order-6 pt-2">
            * <strong>Keyword Search</strong> offers fast, full-text matching. <strong>Dropdown Filters</strong> provide precise category selection.
        </p>

    </div>
</section>