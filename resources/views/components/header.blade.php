<header class="bg-indigo-700 shadow-2xl {{ Route::is(['subject.show', 'exam.show']) ? '' : 'sticky' }} top-0 z-50 border-b border-indigo-600/50 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto flex justify-between items-center py-3.5 px-4 sm:px-6 lg:px-8">
        
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center group flex-shrink-0">
            <img src="{{ asset('images/logo.webp') }}" 
                 alt="ExamDAO Logo"
                 class="h-9 sm:h-10 w-auto group-hover:scale-105 transition-transform duration-300">
        </a>

        @if(Route::currentRouteName() === 'questions.show')
            {{-- Centered Share Button --}}
            <div class="flex-1 flex justify-center px-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                id="fb-share-header"
                target="_blank" 
                rel="noopener noreferrer"
                class="bg-[#1877F2] text-white px-3 py-1.5 rounded-full shadow-lg hover:bg-blue-600 transition-all flex items-center gap-1.5 border border-white/20 active:scale-95">
                    
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>

                    <span class="text-xs font-bold tracking-tight">Share</span>
                </a>
            </div>
        @endif

        <nav class="flex items-center gap-4 sm:gap-8">
            {{-- Desktop Navigation --}}
            <ul class="hidden md:flex items-center space-x-1 text-sm font-semibold">
                <li>
                    <a href="{{ url('/') }}" class="px-4 py-2 rounded-full {{ request()->routeIs('home') ? 'text-yellow-400 bg-white/10' : 'text-indigo-50 hover:bg-white/10 hover:text-white' }} transition-all duration-200">Home</a>
                </li>
                <li>
                    <a href="{{ url('/questions') }}" class="px-4 py-2 rounded-full {{ request()->routeIs('questions.index') ? 'text-yellow-400 bg-white/10' : 'text-indigo-50 hover:bg-white/10 hover:text-white' }} transition-all duration-200">Questions</a>
                </li>
                
                {{-- Auth-dependent items (Managed by tracker.js) --}}
                <li id="nav-dashboard-item" class="hidden">
                    <a href="{{ route('questions.create') }}" class="px-4 py-2 rounded-full text-indigo-50 hover:bg-white/10 hover:text-white transition-all duration-200">Dashboard</a>
                </li>
                <li id="nav-profile-item" class="hidden">
                    <a href="{{ route('profile.show') }}" class="px-4 py-2 rounded-full {{ request()->routeIs('profile.show') ? 'text-yellow-400 bg-white/10' : 'text-indigo-50 hover:bg-white/10 hover:text-white' }} transition-all duration-200">Profile</a>
                </li>
            </ul>

            {{-- Desktop Auth Section --}}
            <div class="hidden md:block">
                <div id="auth-wrapper-desktop" class="min-w-[80px]">
                    <a href="{{ url('/login') }}" class="bg-yellow-400 text-indigo-900 px-6 py-2 rounded-full text-sm font-bold hover:bg-yellow-300 transition block text-center shadow-md">Login</a>
                </div>
            </div>

            {{-- Mobile Toggle Button --}}
            <button id="menu-toggle" 
                    type="button" 
                    aria-label="Toggle Navigation"
                    class="md:hidden text-indigo-50 hover:text-yellow-400 p-2 rounded-lg hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-400/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </nav>
    </div>

    {{-- Mobile Menu Container --}}
    <div id="mobile-menu" 
         class="md:hidden bg-indigo-800 overflow-hidden transition-all duration-500 ease-in-out max-h-0 opacity-0 border-t border-white/5">
        <ul class="flex flex-col p-4 space-y-2">
            <li>
                <a href="{{ url('/') }}" class="block p-3 rounded-xl text-indigo-50 hover:bg-white/10 hover:text-yellow-400 transition">Home</a>
            </li>
            <li>
                <a href="{{ url('/questions') }}" class="block p-3 rounded-xl text-indigo-50 hover:bg-white/10 hover:text-yellow-400 transition">Questions</a>
            </li>
            
            {{-- Mobile Auth-dependent items --}}
            <li id="mobile-dashboard-item" class="hidden">
                <a href="{{ route('questions.create') }}" class="block p-3 rounded-xl text-indigo-50 hover:bg-white/10">Dashboard</a>
            </li>
            <li id="mobile-profile-item" class="hidden">
                <a href="{{ route('profile.show') }}" class="block p-3 rounded-xl text-indigo-50 hover:bg-white/10">Profile</a>
            </li>
            
            <li id="auth-wrapper-mobile" class="pt-2 border-t border-white/5">
                <a href="{{ url('/login') }}" class="bg-yellow-400 text-indigo-900 p-3 rounded-xl font-bold hover:bg-yellow-300 transition block text-center shadow-lg">Login</a>
            </li>
        </ul>
    </div>

    {{-- Dynamic Logout Form --}}
    <form id="logout-form-dynamic" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</header>