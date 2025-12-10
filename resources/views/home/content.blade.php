<section class="py-16 md:py-24 bg-secondary-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary-800">
                Our Extensive Question Bank Coverage
            </h2>
            <p class="mt-4 text-xl text-secondary-600 max-w-2xl mx-auto">
                Prepared by experts and covering a massive range of exams and institutions.
            </p>
        </div>

        {{-- Stat Counters --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
            
            <div class="text-center p-4">
                <p class="text-4xl sm:text-5xl font-extrabold text-warning-500">
                    150,000+ 
                </p>
                <p class="mt-2 text-lg font-medium text-secondary-700">Past Questions</p>
            </div>

            <div class="text-center p-4">
                <p class="text-4xl sm:text-5xl font-extrabold text-warning-500">
                    300+ 
                </p>
                <p class="mt-2 text-lg font-medium text-secondary-700">Institutions Covered</p>
            </div>

            <div class="text-center p-4">
                <p class="text-4xl sm:text-5xl font-extrabold text-warning-500">
                    15+ 
                </p>
                <p class="mt-2 text-lg font-medium text-secondary-700">Major Subjects</p>
            </div>
            
            <div class="text-center p-4">
                <p class="text-4xl sm:text-5xl font-extrabold text-warning-500">
                    20+ 
                </p>
                <p class="mt-2 text-lg font-medium text-secondary-700">Exam Years</p>
            </div>
        </div>

        {{-- Subject Pillars --}}
        <div class="text-center">
            <h3 class="text-2xl font-bold text-primary-700 mb-6">Key Exam Preparation Areas</h3>
            <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
                {{-- Example Subject Tags --}}
                <a href="{{ route('questions.index', ['q' => 'Physics']) }}" class="bg-primary-100 text-primary-700 font-medium px-4 py-2 rounded-full hover:bg-primary-200 transition">Physics</a>
                <a href="{{ route('questions.index', ['q' => 'Chemistry']) }}" class="bg-primary-100 text-primary-700 font-medium px-4 py-2 rounded-full hover:bg-primary-200 transition">Chemistry</a>
                <a href="{{ route('questions.index', ['q' => 'Math']) }}" class="bg-primary-100 text-primary-700 font-medium px-4 py-2 rounded-full hover:bg-primary-200 transition">Higher Math</a>
                <a href="{{ route('questions.index', ['q' => 'Biology']) }}" class="bg-primary-100 text-primary-700 font-medium px-4 py-2 rounded-full hover:bg-primary-200 transition">Biology</a>
                <a href="{{ route('questions.index') }}" class="bg-primary-600 text-white font-medium px-4 py-2 rounded-full hover:bg-primary-700 transition">Explore All Subjects &rarr;</a>
            </div>
        </div>
    </div>
</section>