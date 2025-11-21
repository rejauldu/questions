{{-- Hero Section --}}
<section class="bg-indigo-700 text-center py-12 md:py-20 lg:py-24 space-y-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Tagline --}}
        <p class="px-4 py-1 mx-auto bg-white/10 text-yellow-400 rounded-full text-xs 
            font-semibold uppercase tracking-widest border border-yellow-400/50 w-max shadow-md">
            Learn From the Top Experts
        </p>

        {{-- Main Headings --}}
        <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-white leading-tight mt-4">
            Learn Anywhere, Anytime
        </h1>

        <h2 class="text-3xl sm:text-4xl font-bold text-yellow-300">
            Empower Your Future
        </h2>

        {{-- Subtext --}}
        <p class="text-white/80 max-w-3xl mx-auto text-lg mt-4">
            Join thousands of learners gaining new skills, advancing careers and shaping a 
            better tomorrow—one lesson at a time.
        </p>

        {{-- Search Bar & Primary CTA --}}
        <div class="flex flex-col items-center space-y-6 mt-8">
            
            <!-- Search Bar -->
            <div
                class="flex items-center bg-white rounded-full shadow-2xl px-6 py-3 w-full max-w-lg
                    transition-all duration-200 
                    focus-within:ring-4 focus-within:ring-yellow-400/70">

                <svg class="w-6 h-6 text-gray-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>

                <input
                    type="text"
                    placeholder="Search your Course or Topic..."
                    class="w-full text-gray-800 placeholder-gray-500 text-lg
                        border-none outline-none focus:outline-none 
                        focus:ring-0 focus:border-0 p-0 m-0" />
            </div>

            <!-- New Questions Button -->
            <!-- Assume the button links to the question archive page -->
            <a href="{{ url('/questions') }}"
                class="inline-block bg-yellow-400 text-indigo-800 text-xl font-extrabold 
                       px-10 py-3 rounded-full shadow-lg transition duration-300 
                       hover:bg-yellow-300 hover:shadow-xl transform hover:scale-105 
                       tracking-wider uppercase">
                Browse Questions
            </a>
        </div>


        {{-- Decorative Icons Row --}}
        <div class="flex justify-center gap-12 pt-8 md:pt-12 lg:pt-16">
            <svg class="w-10 h-10 text-yellow-300 opacity-80 animate-pulse" fill="currentColor"
                viewBox="0 0 24 24">
                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02
                    L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z">
                </path>
            </svg>

            <svg class="w-12 h-12 text-yellow-300 opacity-80" fill="currentColor"
                viewBox="0 0 24 24">
                <path d="M10 12l2 2l-2 2l-2-2zM18 6l2 2l-2 2l-2-2zM6 6l2 2l-2 2l-2-2z"></path>
            </svg>
        </div>

    </div>
</section>