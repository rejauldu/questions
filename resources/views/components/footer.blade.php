<footer class="bg-indigo-900 text-white pt-12 pb-6 mt-16 rounded-t-2xl shadow-inner">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Grid adjusted for mobile: now 2 columns on mobile, 4 or 5 on desktop --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 border-b border-indigo-700 pb-10">
            
            {{-- 1. Brand/Description (HIDDEN ON MOBILE) --}}
            <div class="hidden md:block col-span-2 lg:col-span-2 space-y-4">
                {{-- Brand Logo/Title --}}
                <a href="{{ url('/') }}" class="text-3xl font-black text-yellow-400 hover:text-yellow-300 transition duration-150">ExamDao</a>
                
                {{-- Description (Improved contrast with text-gray-300) --}}
                <p class="text-gray-300 text-sm max-w-sm">
                    Your trusted archive for <strong>Past Exam Questions</strong> and verified solutions across all educational boards and institutions in Bangladesh.
                </p>
                
                {{-- Social Links --}}
                <div class="flex space-x-4">
                    {{-- Facebook --}}
                    <a href="https://facebook.com/examdao" target="_blank" aria-label="Facebook" class="text-gray-300 hover:text-yellow-400 transition duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.33 6.75 7.955v-5.625H5.07V9.022h1.85V7.124c0-1.832 1.054-2.831 2.766-2.831.824 0 1.649.155 1.649.155v1.8H9.72c-.933 0-1.12.579-1.12 1.124v1.547h2.16l-.353 2.102h-1.808v5.625C13.074 15.378 16 12.065 16 8.05z"/>
                        </svg>
                    </a>
                    {{-- WhatsApp --}}
                    <a href="https://wa.me/8801924974960" target="_blank" aria-label="WhatsApp" class="text-gray-300 hover:text-green-400 transition duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M13.6 2.302A7.994 7.994 0 0 0 8 0C3.627 0 .068 3.513.002 7.899l-.001.018v1.17a8 8 0 0 0 5.8 7.935l.024.015h.001A8 8 0 0 0 16 8a7.99 7.99 0 0 0-2.4-5.698M8 15a7 7 0 1 1 0-14 7 7 0 0 1 0 14M9.5 7.5v1H11V8.5a.5.5 0 0 0-1 0V8h-.5v-.5a.5.5 0 0 0-1 0V8h-.5V7.5H4v1H5.5v-1H4z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- 2. Quick Access (REMAINS ON MOBILE) --}}
            <div class="space-y-3">
                <h4 class="text-lg font-semibold text-white mb-3">Quick Access</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('questions.index') }}" class="text-gray-300 hover:text-yellow-400 transition">Filter Search</a></li>
                    <li><a href="{{ route('questions.index') }}" class="text-gray-300 hover:text-yellow-400 transition">Text Search</a></li>
                    
                    {{-- Conditional Link --}}
                    @auth
                        <li><a href="{{ url('/chatbot') }}" class="text-gray-300 hover:text-yellow-400 transition">Chatbot</a></li>
                    @endauth
                    @guest
                        <li><a href="{{ url('/register') }}" class="text-gray-300 hover:text-yellow-400 transition">Registration</a></li>
                    @endguest
                    
                </ul>
            </div>

            {{-- 3. Exam Boards (HIDDEN ON MOBILE) --}}
            <div class="hidden md:block space-y-3">
                <h4 class="text-lg font-semibold text-white mb-3">Board</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('questions.index', ['q' => 'SSC']) }}" class="text-gray-300 hover:text-yellow-400 transition">SSC (Secondary)</a></li>
                    <li><a href="{{ route('questions.index', ['q' => 'HSC']) }}" class="text-gray-300 hover:text-yellow-400 transition">HSC (Higher Secondary)</a></li>
                    <li><a href="{{ route('questions.index', ['q' => 'Admission']) }}" class="text-gray-300 hover:text-yellow-400 transition">University Admissions</a></li>
                    <li><a href="{{ route('questions.index', ['q' => 'BCS']) }}" class="text-gray-300 hover:text-yellow-400 transition">Professional Exams</a></li>
                </ul>
            </div>

            {{-- 4. Legal & Info (REMAINS ON MOBILE) --}}
            <div class="space-y-3">
                <h4 class="text-lg font-semibold text-white mb-3">Legal & Info</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ url('/about') }}" class="text-gray-300 hover:text-yellow-400 transition">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-yellow-400 transition">Contact Support</a></li>
                    <li><a href="{{ url('/privacy') }}" class="text-gray-300 hover:text-yellow-400 transition">Privacy Policy</a></li>
                    <li><a href="{{ url('/terms') }}" class="text-gray-300 hover:text-yellow-400 transition">Terms of Service</a></li>
                </ul>
            </div>

        </div>

        <div class="mt-6 pt-4 border-t border-indigo-800 text-center">
            <p class="text-sm text-gray-400">
                &copy; {{ date('Y') }} <strong>ExamDao</strong>. All Rights Reserved.
            </p>
        </div>
    </div>
</footer>