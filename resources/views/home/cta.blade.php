<section class="py-16 md:py-20 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-secondary-900 mb-4">
            Ready to Accelerate Your Exam Success?
        </h2>
        <p class="text-lg text-secondary-600 mb-8">
            Start preparing immediately with the most comprehensive collection of past questions and smart tools available.
        </p>

        {{-- Final CTAs --}}
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            
            {{-- Primary CTA: Go back to search (since it's the core action) --}}
            <a href="{{ route('questions.index') }}"
                class="inline-flex items-center justify-center bg-primary-600 text-white text-lg font-bold 
                       px-8 py-3 rounded-xl shadow-lg transition duration-300 
                       hover:bg-primary-700 hover:shadow-xl transform hover:scale-[1.02]">
                Start Your Search Now &rarr;
            </a>

            {{-- Secondary CTA: Sign Up/Register --}}
            <a href="{{ route('register') }}" 
                class="inline-flex items-center justify-center bg-secondary-200 text-primary-700 text-lg font-semibold 
                       px-8 py-3 rounded-xl shadow-md transition duration-300 
                       hover:bg-secondary-300 hover:text-primary-800">
                Create a Free Account
            </a>
        </div>
    </div>
</section>