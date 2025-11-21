@extends('layout')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-8 border-b pb-3 border-indigo-300 tracking-tight">
            Comprehensive Question Archive
        </h1>

        @foreach ($posts as $index => $post)
            <!-- Start Question Card -->
            <a href="{{ route('questions.show', $post->id) }}" class="block mb-8 group">
                <div class="p-6 bg-white rounded-xl shadow-2xl hover:shadow-3xl transition duration-300 border-t-4 border-indigo-600 transform hover:-translate-y-1">
                    
                    <!-- Question/Article Content -->
                    <div class="text-lg text-gray-800 mb-5 pb-4 border-b border-gray-100">
                        <span class="font-black text-indigo-600 text-xl mr-3">{{ $index + 1 }}.</span>
                        {!! $post->article !!}
                    </div>

                    <!-- Options -->
                    <div class="space-y-3 text-gray-700 text-base">
                        <!-- Option A -->
                        <p class="p-3 rounded-lg bg-gray-50 border border-gray-200 group-hover:bg-indigo-50 transition duration-150">
                            <span class="font-bold text-indigo-500 min-w-4 inline-block">ক)</span> {!! $post->a !!}
                        </p>
                        <!-- Option B -->
                        <p class="p-3 rounded-lg bg-gray-50 border border-gray-200 group-hover:bg-indigo-50 transition duration-150">
                            <span class="font-bold text-indigo-500 min-w-4 inline-block">খ)</span> {{ $post->b }}
                        </p>
                        <!-- Option C -->
                        <p class="p-3 rounded-lg bg-gray-50 border border-gray-200 group-hover:bg-indigo-50 transition duration-150">
                            <span class="font-bold text-indigo-500 min-w-4 inline-block">গ)</span> {{ $post->c }}
                        </p>
                        <!-- Option D -->
                        <p class="p-3 rounded-lg bg-gray-50 border border-gray-200 group-hover:bg-indigo-50 transition duration-150">
                            <span class="font-bold text-indigo-500 min-w-4 inline-block">ঘ)</span> {{ $post->d }}
                        </p>
                    </div>

                    <!-- Answer Reveal -->
                    <div class="mt-6 p-4 bg-yellow-50 text-yellow-800 rounded-xl border-2 border-yellow-300 font-semibold flex items-center shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-yellow-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="uppercase text-sm tracking-wider">Answer:</span> 
                        <span class="ml-2 text-xl font-extrabold text-green-700">{{ $post->answer }}</span>
                    </div>
                </div>
            </a>
            <!-- End Question Card -->
        @endforeach

        <!-- MathJax Script Inclusion -->
        <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                MathJax.typesetPromise();
            });
            MathJax.typesetPromise();
        </script>
    </div>
@endsection