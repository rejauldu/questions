<section class="py-16 md:py-20 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-secondary-900 mb-4">
            Ready to Accelerate Your Exam Success?
        </h2>
        <p class="text-lg text-secondary-600 mb-8">
            Start preparing immediately with the most comprehensive collection of past questions and smart tools available.
        </p>

        {{-- Final CTAs --}}
        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-10">
            
            <a href="{{ route('exam.show', ['hsc']) }}"
                class="inline-flex items-center justify-center bg-primary-600 text-white text-lg font-bold 
                       px-8 py-3 rounded-xl shadow-lg transition duration-300 
                       hover:bg-primary-700 hover:shadow-xl transform hover:scale-[1.02]">
                Search HSC Question Bank &rarr;
            </a>

            <a href="{{ route('register') }}" 
                class="inline-flex items-center justify-center bg-secondary-200 text-primary-700 text-lg font-semibold 
                       px-8 py-3 rounded-xl shadow-md transition duration-300 
                       hover:bg-secondary-300 hover:text-primary-800">
                Join ExamDao for Free
            </a>
        </div>

        {{-- SEO Contextual Block (Strong tags reduced for better readability) --}}
        <div class="mt-12 text-sm text-secondary-500 max-w-4xl mx-auto leading-relaxed border-t border-gray-100 pt-8">
            <p>
                ExamDao provides a centralized hub for students targeting Board Exams (SSC, HSC), 
                University Admission, and Competitive Job Exams (BCS). 
                By practicing with our verified past year questions and using our 
                <strong>AI-driven solutions</strong>, candidates can identify exam patterns and boost their 
                confidence for the 2025-2026 academic sessions.
            </p>
        </div>
    </div>
</section>