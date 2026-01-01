<header class="bg-indigo-700 shadow-2xl sticky top-0 z-50 border-b border-indigo-600/50 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto flex justify-between items-center py-3.5 px-4 sm:px-6 lg:px-8">
        
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center group">
            <img src="{{ asset('images/logo.webp') }}" 
                 alt="ExamDAO Logo"
                 class="h-9 sm:h-10 w-auto group-hover:scale-105 transition-transform duration-300">
        </a>

        <nav class="flex items-center gap-4 sm:gap-8">
            {{-- Desktop Navigation --}}
            <ul class="hidden md:flex items-center space-x-1 text-sm font-semibold">
                <li>
                    <a href="{{ url('/') }}" class="px-4 py-2 rounded-full {{ request()->routeIs('home') ? 'text-yellow-400 bg-white/10' : 'text-indigo-50 hover:bg-white/10 hover:text-white' }} transition-all duration-200">Home</a>
                </li>
                <li>
                    <a href="{{ url('/questions') }}" class="px-4 py-2 rounded-full {{ request()->routeIs('questions.index') ? 'text-yellow-400 bg-white/10' : 'text-indigo-50 hover:bg-white/10 hover:text-white' }} transition-all duration-200">Questions</a>
                </li>
                <li>
                    <a href="{{ url('/chatbot') }}" class="px-4 py-2 rounded-full {{ request()->routeIs('chatbot') ? 'text-yellow-400 bg-white/10' : 'text-indigo-50 hover:bg-white/10 hover:text-white' }} transition-all duration-200">Chatbot</a>
                </li>
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
                    {{-- Default State: Show Login --}}
                    <a href="{{ url('/login') }}" class="bg-yellow-400 text-indigo-900 px-6 py-2 rounded-full text-sm font-bold hover:bg-yellow-300 transition block text-center shadow-md">Login</a>
                </div>
            </div>

            {{-- Mobile Toggle --}}
            <button id="menu-toggle" class="md:hidden text-indigo-50 hover:text-yellow-400 p-2 rounded-lg hover:bg-white/10 transition-colors focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </nav>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" 
        class="md:hidden bg-indigo-800 overflow-hidden transition-all duration-500 ease-in-out max-h-0 opacity-0 border-t border-white/5">
        <ul class="flex flex-col p-4 space-y-2">
            <li><a href="{{ url('/') }}" class="block p-3 rounded-xl text-indigo-50 hover:bg-white/10 hover:text-yellow-400 transition">Home</a></li>
            <li><a href="{{ url('/questions') }}" class="block p-3 rounded-xl text-indigo-50 hover:bg-white/10 hover:text-yellow-400 transition">Questions</a></li>
            <li><a href="{{ url('/chatbot') }}" class="block p-3 rounded-xl text-indigo-50 hover:bg-white/10 hover:text-yellow-400 transition">Chatbot</a></li>
            <li id="mobile-dashboard-item" class="hidden">
                <a href="{{ route('questions.create') }}" class="block p-3 rounded-xl text-indigo-50 hover:bg-white/10">Dashboard</a>
            </li>
            <li id="mobile-profile-item" class="hidden">
                <a href="{{ url('/profile') }}" class="block p-3 rounded-xl text-indigo-50 hover:bg-white/10">Profile</a>
            </li>
            <li id="auth-wrapper-mobile" class="pt-2 border-t border-white/5">
                {{-- Default State: Show Login --}}
                <a href="{{ url('/login') }}" class="bg-yellow-400 text-indigo-900 p-3 rounded-xl font-bold hover:bg-yellow-300 transition block text-center shadow-lg">Login</a>
            </li>
        </ul>
    </div>

    <form id="logout-form-dynamic" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</header>