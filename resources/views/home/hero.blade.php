<section class="relative bg-primary-800 pt-6 pb-8 sm:pt-16 sm:pb-20 overflow-hidden">
    {{-- Subtle background glow --}}
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-10">
        <div class="absolute -top-12 -left-12 w-64 h-64 bg-warning-400 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 flex flex-col items-center text-center">


        {{-- Personalized Container --}}
        <div id="personalized-hero-content" class="w-full flex flex-col items-center transition-all duration-700 ease-in-out">
            {{-- DEFAULT CONTENT (Shown while loading or if no intent found) --}}
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 text-primary-100 rounded-full text-[10px] font-bold uppercase tracking-wider mb-4 sm:mb-6">
                <span class="w-1.5 h-1.5 bg-warning-400 rounded-full animate-pulse"></span>
                Past Questions & AI Guidance
            </div>

            <h1 class="text-2xl sm:text-5xl font-extrabold text-white leading-tight mb-6 max-w-3xl px-2">
                Master <span class="text-warning-400">Past Questions</span> for SSC, HSC & BCS
            </h1>
        </div>

        {{-- Search Container - Slimmer padding --}}
        <div class="w-full max-w-xl bg-white/5 backdrop-blur-md p-1.5 rounded-xl border border-white/10 mb-8">
            <form action="{{ route('questions.index') }}" method="GET" class="relative">
                <div class="flex items-center bg-white rounded-lg overflow-hidden p-0.5 shadow-lg">
                    <input
                        type="text"
                        name="q"
                        placeholder="Search: 'HSC ICT 2024'"
                        class="w-full text-slate-800 placeholder-slate-400 text-sm sm:text-base py-2.5 px-4 border-none outline-none focus:ring-0 bg-transparent"
                        value="{{ $search ?? '' }}" />
                    
                    <button type="submit" class="bg-primary-600 hover:bg-primary-500 text-white px-4 py-2.5 rounded-md transition-all active:scale-95">
                        <x-icons.search class="w-5 h-5"/>
                    </button>
                </div>
            </form>
        </div>

        {{-- Institution Grid - Compact 2x2 for mobile --}}
        <div class="w-full max-w-4xl">
            <!-- Changed grid to flex and added justify-center -->
            <div class="flex flex-wrap justify-center gap-2.5">
                @foreach($institutions as $inst)
                <a href="{{ route('exam.show', $inst->slug) }}" 
                class="group flex items-center gap-3 p-3 bg-white/10 border border-white/10 rounded-xl transition-all hover:bg-white hover:shadow-xl w-[calc(50%-1.25rem)] md:w-[calc(25%-1.25rem)] min-w-[150px]">
                    
                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 text-white font-bold text-xs group-hover:bg-primary-100 group-hover:text-primary-600">
                        {{ strtoupper(substr($inst->name, 0, 1)) }}
                    </div>

                    <span class="text-xs font-bold text-white group-hover:text-slate-800 truncate text-left">
                        {{ institution($inst->name) }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Action Links - Tighter spacing --}}
        <div class="mt-6 flex items-center justify-center gap-6">
            <a href="{{ url('/chatbot') }}" class="flex items-center gap-1.5 text-primary-200 hover:text-warning-400 transition-colors text-[11px] font-bold uppercase tracking-tight">
                <x-icons.chatbot class="w-4 h-4"/>
                AI Assistant
            </a>
            <div class="w-px h-3 bg-white/20"></div>
            <a href="{{ route('search') }}" class="flex items-center gap-1.5 text-primary-200 hover:text-warning-400 transition-colors text-[11px] font-bold uppercase tracking-tight">
                <x-icons.funnel class="w-4 h-4"/>
                Filters
            </a>
        </div>
    </div>
</section>