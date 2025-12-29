<section class="py-16 md:py-24 bg-secondary-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary-800">
                Our Extensive Question Bank Coverage
            </h2>
            <p class="mt-4 text-xl text-secondary-600 max-w-3xl mx-auto">
                ExamDao is Bangladesh's most comprehensive digital archive for SSC, HSC, Admission, and BCS preparation. We provide organized access to thousands of past papers and verified solutions.
            </p>
        </div>

        {{-- SEO Text Block --}}
        <div class="prose prose-indigo max-w-none mb-16 text-secondary-700 text-center">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                <div>
                    <h3 class="text-xl font-bold text-primary-700">Strategic Prep for SSC & HSC</h3>
                    <p>
                        Success in board exams starts with understanding patterns. Our SSC question bank and HSC question bank modules allow students to practice chapter-wise. From Physics and Higher Math to English and ICT, we cover all major subjects across all education boards in Bangladesh.
                    </p>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-primary-700">Admission & Competitive Exams</h3>
                    <p>
                        Gaining a seat in top universities or securing a government job requires precision. ExamDao offers specialized resources for University Admission (DU, Medical, Engineering), National University (NU) degree exams, and the BCS Preliminary test. Our platform ensures you are always practicing with relevant, updated content.
                    </p>
                </div>
            </div>
        </div>

        {{-- Stat Counters --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16">
            <div class="text-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100">
                <p class="text-4xl sm:text-5xl font-extrabold text-warning-500">150,000+</p>
                <p class="mt-2 text-lg font-medium text-secondary-700">Past Questions</p>
            </div>

            <div class="text-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100">
                <p class="text-4xl sm:text-5xl font-extrabold text-warning-500">300+</p>
                <p class="mt-2 text-lg font-medium text-secondary-700">Institutions</p>
            </div>

            <div class="text-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100">
                <p class="text-4xl sm:text-5xl font-extrabold text-warning-500">15+</p>
                <p class="mt-2 text-lg font-medium text-secondary-700">Major Subjects</p>
            </div>
            
            <div class="text-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100">
                <p class="text-4xl sm:text-5xl font-extrabold text-warning-500">20+</p>
                <p class="mt-2 text-lg font-medium text-secondary-700">Exam Years</p>
            </div>
        </div>

        {{-- Subject Pillars (Updated with SEO Friendly Routes) --}}
        <div class="text-center bg-primary-50 rounded-3xl p-8 sm:p-12 border border-primary-100">
            <h3 class="text-2xl font-bold text-primary-700 mb-2">Key Exam Preparation Areas</h3>
            <p class="text-secondary-600 mb-8 max-w-2xl mx-auto">
                Quickly jump into specific subject archives for SSC, HSC, and Admission tests.
            </p>
            <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
                <a href="{{ route('subject.show', 'physics') }}" class="bg-white text-primary-700 border border-primary-200 font-medium px-5 py-2.5 rounded-full hover:bg-primary-600 hover:text-white transition-all duration-300 shadow-sm">Physics</a>
                
                <a href="{{ route('subject.show', 'chemistry') }}" class="bg-white text-primary-700 border border-primary-200 font-medium px-5 py-2.5 rounded-full hover:bg-primary-600 hover:text-white transition-all duration-300 shadow-sm">Chemistry</a>
                
                <a href="{{ route('subject.show', 'math') }}" class="bg-white text-primary-700 border border-primary-200 font-medium px-5 py-2.5 rounded-full hover:bg-primary-600 hover:text-white transition-all duration-300 shadow-sm">Higher Math</a>
                
                <a href="{{ route('subject.show', 'biology') }}" class="bg-white text-primary-700 border border-primary-200 font-medium px-5 py-2.5 rounded-full hover:bg-primary-600 hover:text-white transition-all duration-300 shadow-sm">Biology</a>
                
                <a href="{{ route('subject.show', 'english') }}" class="bg-white text-primary-700 border border-primary-200 font-medium px-5 py-2.5 rounded-full hover:bg-primary-600 hover:text-white transition-all duration-300 shadow-sm">English</a>
                
                <a href="{{ route('subject.show', 'ict') }}" class="bg-white text-primary-700 border border-primary-200 font-medium px-5 py-2.5 rounded-full hover:bg-primary-600 hover:text-white transition-all duration-300 shadow-sm">ICT</a>
                
                <a href="{{ route('questions.index') }}" class="bg-primary-600 text-white font-bold px-6 py-2.5 rounded-full hover:bg-primary-700 transition-all duration-300 shadow-md">Browse All Exam Subjects &rarr;</a>
            </div>
        </div>
    </div>
</section>