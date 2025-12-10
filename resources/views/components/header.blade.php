<header class="bg-indigo-700 shadow-xl sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center py-4 px-4 sm:px-6 lg:px-8">
        
        <a href="{{ url('/') }}" class="text-3xl font-extrabold text-white tracking-wide hover:text-yellow-400 transition duration-300 flex items-center">
            
        <img src="{{ asset('images/logo.webp') }}" 
                alt="ExamDAO Logo"
                class="h-8 sm:h-9 w-auto hover:opacity-80 transition duration-300">
        </a>

        <nav class="flex items-center space-x-6">
            
            <ul class="hidden md:flex space-x-8 text-base font-medium">
                <li>
                    <a href="{{ url('/') }}"
                        class="{{ request()->routeIs('home') ? 'text-yellow-400 font-bold border-b-2 border-yellow-400 pb-1' : 'text-white hover:text-yellow-300' }} transition duration-200 ease-in-out">
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ url('/questions') }}"
                        class="{{ request()->routeIs('questions.index') ? 'text-yellow-400 font-bold border-b-2 border-yellow-400 pb-1' : 'text-white hover:text-yellow-300' }} transition duration-200 ease-in-out">
                        Questions
                    </a>
                </li>
                <li>
                    <a href="{{ url('/chatbot') }}"
                        class="{{ request()->routeIs('chatbot') ? 'text-yellow-400 font-bold border-b-2 border-yellow-400 pb-1' : 'text-white hover:text-yellow-300' }} transition duration-200 ease-in-out">
                        Chatbot
                    </a>
                </li>
                <li>
                    <a href="{{ url('/contact') }}"
                        class="{{ request()->routeIs('contact') ? 'text-yellow-400 font-bold border-b-2 border-yellow-400 pb-1' : 'text-white hover:text-yellow-300' }} transition duration-200 ease-in-out">
                        Contact
                    </a>
                </li>
                {{-- Add a Dashboard/Profile Link for Logged-In Users Here --}}
                @auth
                <li>
                    <a href="{{ url('/dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'text-yellow-400 font-bold border-b-2 border-yellow-400 pb-1' : 'text-white hover:text-yellow-300' }} transition duration-200 ease-in-out">
                        Dashboard
                    </a>
                </li>
                @endauth
            </ul>

            <div class="hidden md:inline-block">
                @guest
                    <a href="{{ url('/register') }}"
                        class="bg-yellow-400 text-indigo-800 px-6 py-2.5 rounded-full text-base font-bold shadow-md hover:bg-yellow-300 transition duration-150 transform hover:scale-[1.02]">
                        Registration
                    </a>
                @endguest
                
                @auth
                    {{-- Secure Logout Form (POST request) --}}
                    <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" 
                       onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();"
                       class="bg-red-500 text-white px-6 py-2.5 rounded-full text-base font-bold shadow-md hover:bg-red-400 transition duration-150 transform hover:scale-[1.02]">
                        Logout
                    </a>
                @endauth
            </div>

            <div class="md:hidden">
                <button id="menu-toggle" class="text-white focus:outline-none p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </nav>
    </div>

    <div id="mobile-menu" class="hidden md:hidden bg-indigo-800 transition-all duration-300 ease-in-out">
        <ul class="flex flex-col p-4 space-y-1">
            <li>
                <a href="{{ url('/') }}"
                    class="block p-3 rounded-lg {{ request()->routeIs('home') ? 'bg-indigo-900 text-yellow-400 font-bold' : 'text-white hover:bg-indigo-900' }} transition duration-150">
                    Home
                </a>
            </li>
            <li>
                <a href="{{ url('/questions') }}"
                    class="block p-3 rounded-lg {{ request()->routeIs('questions.index') ? 'bg-indigo-900 text-yellow-400 font-bold' : 'text-white hover:bg-indigo-900' }} transition duration-150">
                    Questions
                </a>
            </li>
            <li>
                <a href="{{ url('/chatbot') }}"
                    class="block p-3 rounded-lg {{ request()->routeIs('chatbot') ? 'bg-indigo-900 text-yellow-400 font-bold' : 'text-white hover:bg-indigo-900' }} transition duration-150">
                    Chatbot
                </a>
            </li>
            <li>
                <a href="{{ url('/contact') }}"
                    class="block p-3 rounded-lg {{ request()->routeIs('contact') ? 'bg-indigo-900 text-yellow-400 font-bold' : 'text-white hover:bg-indigo-900' }}">
                    Contact
                </a>
            </li>
            {{-- Added Dashboard link to mobile menu --}}
            @auth
            <li>
                <a href="{{ url('/dashboard') }}"
                    class="block p-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-indigo-900 text-yellow-400 font-bold' : 'text-white hover:bg-indigo-900' }}">
                    Dashboard
                </a>
            </li>
            @endauth

            {{-- Mobile Registration/Logout Logic --}}
            @guest
            <li>
                <a href="{{ url('/register') }}"
                    class="block text-center mt-4 bg-yellow-400 text-indigo-800 px-4 py-3 rounded-full text-sm font-bold hover:bg-yellow-500 transition duration-150 shadow-lg">
                    Registration
                </a>
            </li>
            @endguest
            @auth
            <li>
                {{-- Secure Logout Form (Mobile) --}}
                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                    class="block text-center mt-4 bg-red-500 text-white px-4 py-3 rounded-full text-sm font-bold hover:bg-red-400 transition duration-150 shadow-lg">
                    Logout
                </a>
            </li>
            @endauth
        </ul>
    </div>

    <script>
        const toggleButton = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (toggleButton && mobileMenu) {
            toggleButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</header>