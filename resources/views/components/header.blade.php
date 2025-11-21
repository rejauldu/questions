<header class="bg-indigo-700 shadow-xl sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center py-4 px-4 sm:px-6 lg:px-8">
        
        <!-- Logo/Brand Name -->
        <a href="{{ url('/') }}" class="text-3xl font-extrabold text-white tracking-wide hover:text-yellow-400 transition duration-300">
            LearNix
        </a>

        <nav class="flex items-center space-x-6">
            
            <!-- Desktop Navigation Links -->
            <ul class="hidden md:flex space-x-8 text-base font-medium">
                <li>
                    <a href="{{ url('/') }}"
                        class="{{ request()->routeIs('home') ? 'text-yellow-400 font-bold border-b-2 border-yellow-400 pb-1' : 'text-white hover:text-yellow-300' }} transition duration-200 ease-in-out">
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ url('/questions') }}"
                        class="{{ request()->routeIs('questions') ? 'text-yellow-400 font-bold border-b-2 border-yellow-400 pb-1' : 'text-white hover:text-yellow-300' }} transition duration-200 ease-in-out">
                        Questions
                    </a>
                </li>
                <li>
                    <a href="{{ url('/instructors') }}"
                        class="{{ request()->routeIs('instructors') ? 'text-yellow-400 font-bold border-b-2 border-yellow-400 pb-1' : 'text-white hover:text-yellow-300' }} transition duration-200 ease-in-out">
                        Instructors
                    </a>
                </li>
                <li>
                    <a href="{{ url('/blog') }}"
                        class="{{ request()->routeIs('blog') ? 'text-yellow-400 font-bold border-b-2 border-yellow-400 pb-1' : 'text-white hover:text-yellow-300' }} transition duration-200 ease-in-out">
                        Blog
                    </a>
                </li>
                <li>
                    <a href="{{ url('/contact') }}"
                        class="{{ request()->routeIs('contact') ? 'text-yellow-400 font-bold border-b-2 border-yellow-400 pb-1' : 'text-white hover:text-yellow-300' }} transition duration-200 ease-in-out">
                        Contact
                    </a>
                </li>
            </ul>

            <!-- Registration Button -->
            <a href="{{ url('/registration') }}"
                class="bg-yellow-400 text-indigo-800 px-6 py-2.5 rounded-full text-base font-bold shadow-md hover:bg-yellow-300 transition duration-150 transform hover:scale-[1.02] hidden md:inline-block">
                Registration
            </a>

            <!-- Mobile Menu Toggle -->
            <div class="md:hidden">
                <button id="menu-toggle" class="text-white focus:outline-none p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </nav>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div id="mobile-menu" class="hidden md:hidden bg-indigo-800 transition-all duration-300 ease-in-out">
        <ul class="flex flex-col p-4 space-y-1">
            <li>
                <a href="{{ url('/') }}"
                    class="block p-3 rounded-lg {{ request()->routeIs('home') ? 'bg-indigo-900 text-yellow-400 font-bold' : 'text-white hover:bg-indigo-900' }} transition duration-150">
                    Home
                </a>
            </li>
            <li>
                <a href="{{ url('/courses') }}"
                    class="block p-3 rounded-lg {{ request()->routeIs('courses') ? 'bg-indigo-900 text-yellow-400 font-bold' : 'text-white hover:bg-indigo-900' }}">
                    Courses
                </a>
            </li>
            <li>
                <a href="{{ url('/instructors') }}"
                    class="block p-3 rounded-lg {{ request()->routeIs('instructors') ? 'bg-indigo-900 text-yellow-400 font-bold' : 'text-white hover:bg-indigo-900' }}">
                    Instructors
                </a>
            </li>
            <li>
                <a href="{{ url('/blog') }}"
                    class="block p-3 rounded-lg {{ request()->routeIs('blog') ? 'bg-indigo-900 text-yellow-400 font-bold' : 'text-white hover:bg-indigo-900' }}">
                    Blog
                </a>
            </li>
            <li>
                <a href="{{ url('/contact') }}"
                    class="block p-3 rounded-lg {{ request()->routeIs('contact') ? 'bg-indigo-900 text-yellow-400 font-bold' : 'text-white hover:bg-indigo-900' }}">
                    Contact
                </a>
            </li>
            <li>
                <a href="{{ url('/registration') }}"
                    class="block text-center mt-4 bg-yellow-400 text-indigo-800 px-4 py-3 rounded-full text-sm font-bold hover:bg-yellow-500 transition duration-150 shadow-lg">
                    Registration
                </a>
            </li>
        </ul>
    </div>

    <script>
        // Note: The script is placed here to keep the HTML section self-contained, 
        // but typically should be deferred or placed at the end of the <body> for performance.
        const toggleButton = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (toggleButton && mobileMenu) {
            toggleButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</header>