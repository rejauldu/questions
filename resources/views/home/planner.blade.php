<section class="py-16 md:py-24 bg-primary-700">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">
            Don't Miss the Next Critical Deadline
        </h2>
        <p class="text-xl text-primary-200 max-w-3xl mx-auto">
            We continuously update official schedules for all major admission tests, board, and university exams. Plan your preparation perfectly.
        </p>
        
        {{-- Secondary CTA to Schedules --}}
        <a href="{{ url('/chatbot') }}" 
           class="mt-8 inline-flex items-center justify-center bg-warning-400 text-secondary-900 text-lg font-extrabold 
                  px-8 py-3 rounded-xl shadow-2xl transition duration-300 
                  hover:bg-warning-300 hover:shadow-warning-300/50 transform hover:scale-[1.03]"> 
            <x-icons.calendar class="sm:h-6 sm:w-6" />
            Check Latest Exam Schedules
        </a>
    </div>
</section>