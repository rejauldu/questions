<footer class="bg-indigo-900 text-white pt-12 pb-6 mt-16 rounded-t-2xl shadow-inner">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 border-b border-indigo-700 pb-10">
            
            {{-- 1. Brand/Description --}}
            <div class="hidden md:block col-span-2 lg:col-span-2 space-y-4">
                <a href="{{ url('/') }}" class="text-3xl font-black text-yellow-400 hover:text-yellow-300 transition duration-150">ExamDao</a>
                
                <p class="text-gray-300 text-sm max-w-sm leading-relaxed">
                    ExamDao is Bangladesh's premier digital platform for SSC and HSC exam preparation. We provide a massive archive of Past Exam Questions, university Admission test papers, and verified BCS solutions to help students excel in their academic and professional journeys.
                </p>
                
                {{-- Social Links --}}
                <div class="flex space-x-4">
                    <a href="https://facebook.com/examdaobd" target="_blank" aria-label="Follow ExamDao on Facebook" class="text-gray-300 hover:text-yellow-400 transition duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.33 6.75 7.955v-5.625H5.07V9.022h1.85V7.124c0-1.832 1.054-2.831 2.766-2.831.824 0 1.649.155 1.649.155v1.8H9.72c-.933 0-1.12.579-1.12 1.124v1.547h2.16l-.353 2.102h-1.808v5.625C13.074 15.378 16 12.065 16 8.05z"/>
                        </svg>
                    </a>
                    <a href="https://whatsapp.com/channel/0029Vb6n6Xs6BIEZr12GnW41" target="_blank" aria-label="Join our WhatsApp Channel" class="text-gray-300 hover:text-green-400 transition duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 .018 5.396.015 12.03c0 2.12.554 4.189 1.605 6.009l-1.706 6.23 6.374-1.671a11.82 11.82 0 005.76 1.491h.005c6.635 0 12.032-5.396 12.035-12.03a11.794 11.794 0 00-3.41-8.507z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- 2. Smart Tools --}}
            <div class="space-y-3">
                <h4 class="text-lg font-semibold text-white mb-3">Smart Tools</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('questions.index') }}" class="text-gray-300 hover:text-yellow-400 transition">Search Question Bank</a></li>
                    <li><a href="{{ url('/search') }}" class="text-gray-300 hover:text-yellow-400 transition">Subject Filters</a></li>
                    <li><a href="{{ url('/chatbot') }}" class="text-gray-300 hover:text-yellow-400 transition">Smart Chatbot Tutor</a></li>
                    @guest
                        <li><a href="{{ url('/register') }}" class="text-gray-300 hover:text-yellow-400 transition">Join ExamDao Free</a></li>
                    @endguest
                </ul>
            </div>

            {{-- 3. Exam Categories --}}
            <div class="hidden md:block space-y-3">
                <h4 class="text-lg font-semibold text-white mb-3">Exam Categories</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('exam.show', 'ssc') }}" class="text-gray-300 hover:text-yellow-400 transition">SSC Preparation</a></li>
                    <li><a href="{{ route('exam.show', 'hsc') }}" class="text-gray-300 hover:text-yellow-400 transition">HSC Question Bank</a></li>
                    <li><a href="{{ route('exam.show', 'admission') }}" class="text-gray-300 hover:text-yellow-400 transition">Admission Guidance</a></li>
                    <li><a href="{{ route('exam.show', 'bcs') }}" class="text-gray-300 hover:text-yellow-400 transition">BCS Study Materials</a></li>
                </ul>
            </div>

            {{-- 4. Information --}}
            <div class="space-y-3">
                <h4 class="text-lg font-semibold text-white mb-3">Information</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-yellow-400 transition">Learn About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-yellow-400 transition">Contact Our Support</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-gray-300 hover:text-yellow-400 transition">Privacy & Data</a></li>
                    <li><a href="{{ route('terms') }}" class="text-gray-300 hover:text-yellow-400 transition">Terms of Use</a></li>
                </ul>
            </div>

        </div>

        {{-- Bottom Copyright --}}
        <div class="mt-6 pt-4 border-t border-indigo-800 flex flex-col md:flex-row justify-between items-center text-center md:text-left">
            <p class="text-sm text-gray-400">
                &copy; {{ date('Y') }} ExamDao
            </p>
            <p class="text-xs text-gray-500 mt-2 md:mt-0">
                Built with precision for HSC, SSC, and BCS candidates.
            </p>
        </div>
    </div>
</footer>