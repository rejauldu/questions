@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto py-3 sm:py-6 px-2 lg:px-4"> 

    {{-- Search Box --}}
    <form method="GET" action="{{ route('questions.index') }}" class="mb-3 sm:mb-4 flex">
        <input 
            type="text" 
            name="q"
            value="{{ request('q') }}"
            placeholder="Search questions..."
            class="w-full p-3 border border-gray-300 rounded-l-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm border-r-0"
        >
        <button 
            type="submit"
            class="bg-indigo-600 text-white p-2 rounded-r-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 flex items-center justify-center min-w-10"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </form>

    {{-- Questions Container --}}
    <div id="questions-container" class="space-y-3 sm:space-y-4"> 
        @foreach ($posts as $index => $post)
            <a href="{{ route('questions.show', $post->id) }}" class="block group question-card">
                <div class="p-3 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 border-t-3 border-indigo-600 transform hover:bg-gray-100">
                    
                    {{-- Meta --}}
                    <div class="flex justify-end text-right text-xs sm:text-sm font-semibold text-yellow-700 mb-2 sm:mb-3 flex-wrap gap-x-2 sm:gap-x-4">
                        <h4>
                            {{ implode(' - ', array_filter([
                                explode('/', $post->institution->name)[0] ?? null,
                                $post->subject->name ?? null,
                                $post->class ?? null,
                                $post->topic ?? null,
                                $post->board ?? null,
                                $post->year ?? null
                            ])) }}
                        </h4>
                    </div>

                    {{-- Question --}}
                    <div class="px-2 text-sm sm:text-base text-gray-800 mb-2 pb-2 border-b border-gray-100">
                        <span class="font-black text-indigo-600 text-lg mr-2">{{ $index + 1 }}.</span>
                        {!! $post->article !!}
                    </div>

                    {{-- Options --}}
                    <div class="grid grid-cols-1 gap-1 sm:gap-2 text-gray-700 text-xs sm:text-sm"> 
                        <p class="p-2 rounded-md bg-gray-50 border border-gray-200 flex items-start">
                            <span class="font-bold text-indigo-500 min-w-4 inline-block mr-1 sm:mr-2">ক)</span> 
                            <span class="flex-1">{!! $post->a !!}</span>
                        </p>
                        <p class="p-2 rounded-md bg-gray-50 border border-gray-200 flex items-start">
                            <span class="font-bold text-indigo-500 min-w-4 inline-block mr-1 sm:mr-2">খ)</span> 
                            <span class="flex-1">{!! $post->b !!}</span>
                        </p>
                        <p class="p-2 rounded-md bg-gray-50 border border-gray-200 flex items-start">
                            <span class="font-bold text-indigo-500 min-w-4 inline-block mr-1 sm:mr-2">গ)</span> 
                            <span class="flex-1">{!! $post->c !!}</span>
                        </p>
                        <p class="p-2 rounded-md bg-gray-50 border border-gray-200 flex items-start">
                            <span class="font-bold text-indigo-500 min-w-4 inline-block mr-1 sm:mr-2">ঘ)</span> 
                            <span class="flex-1">{!! $post->d !!}</span>
                        </p>
                    </div>

                    {{-- Answer (Always Visible) --}}
                    <div class="mt-2">
                        <div class="p-2 bg-yellow-50 text-yellow-800 rounded-lg border border-yellow-300 font-semibold flex items-center shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-1 sm:mr-2 text-yellow-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="uppercase text-xs tracking-wider">Answer:</span> 
                            <span class="ml-1 text-base font-extrabold text-green-700">{{ $post->answer }}</span>
                        </div>
                    </div>

                </div>
            </a>
        @endforeach
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        MathJax.typesetPromise();
    });
</script>
@endpush
