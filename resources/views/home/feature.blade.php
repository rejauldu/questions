<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary-800">
                Why Choose Our Smart Exam Prep?
            </h2>
            <p class="mt-4 text-xl text-secondary-600 max-w-2xl mx-auto">
                Access a vast library of past questions and schedules with speed, precision, and personalized AI support.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- Feature 1: Keyword Search (Speed) --}}
            <div class="p-6 bg-primary-50 rounded-xl shadow-sm border-t-4 border-warning-400 text-center transition duration-300 hover:shadow-xl">
                <div class="text-warning-600 mx-auto mb-4">
                    <x-icons.search class="sm:w-10 sm:h-10 mx-auto" />
                </div>
                <h3 class="text-xl font-bold text-primary-700 mb-2">Instant Keyword Search</h3>
                <p class="text-secondary-600">
                    Find relevant questions and exam info instantly using natural language. Fastest way to start studying.
                </p>
                <a href="{{ route('questions.index') }}" class="mt-4 inline-block text-primary-600 font-semibold hover:text-primary-800 transition">
                    Start Searching &rarr;
                </a>
            </div>

            {{-- Feature 2: Advanced Filters (Precision) --}}
            <div class="p-6 bg-primary-50 rounded-xl shadow-sm border-t-4 border-primary-600 text-center transition duration-300 hover:shadow-xl">
                <div class="text-primary-600 mx-auto mb-4">
                    <x-icons.funnel class="sm:w-10 sm:h-10 mx-auto mr-auto" />
                </div>
                <h3 class="text-xl font-bold text-primary-700 mb-2">Precise Filter Selection</h3>
                <p class="text-secondary-600">
                    Target your preparation by filtering questions by Institution, Subject, Class, and specific Year.
                </p>
                <a href="{{ route('search') }}" class="mt-4 inline-block text-primary-600 font-semibold hover:text-primary-800 transition">
                    Use Filters &rarr;
                </a>
            </div>

            {{-- Feature 3: AI Chatbot (Guidance) --}}
            <div class="p-6 bg-primary-50 rounded-xl shadow-sm border-t-4 border-warning-400 text-center transition duration-300 hover:shadow-xl">
                <div class="text-warning-600 mx-auto mb-4">
                    <x-icons.chatbot class="sm:w-10 sm:h-10 mr-auto mx-auto"/>
                </div>
                <h3 class="text-xl font-bold text-primary-700 mb-2">Personalized AI Guidance</h3>
                <p class="text-secondary-600">
                    Ask the Chatbot for explanations, summaries, or related concepts to deepen your understanding.
                </p>
                <a href="{{ url('/chatbot') }}" class="mt-4 inline-block text-primary-600 font-semibold hover:text-primary-800 transition">
                    Ask the AI &rarr;
                </a>
            </div>
        </div>
    </div>
</section>