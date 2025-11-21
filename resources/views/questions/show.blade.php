@extends('layout')

@section('content')
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumbs / Back Link (Enhance Navigation) -->
        <a href="{{ url('/questions') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition duration-150 mb-6 font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Archive
        </a>

        <!-- Question Detail Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 lg:p-10 mb-10 border-t-8 border-indigo-600">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 border-b pb-4">
                Question Details: <span class="text-indigo-600">ID: {{ $post->id ?? 'Q-1234' }}</span>
            </h1>
            
            <!-- Question Content -->
            <div class="text-xl text-gray-800 mb-8 leading-relaxed">
                {!! $post->article !!}
            </div>

            <!-- Options -->
            <div class="space-y-4 text-gray-700 text-lg">
                <p class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                    <span class="font-bold text-indigo-500 min-w-4 inline-block mr-2">ক)</span> {!! $post->a !!}
                </p>
                <p class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                    <span class="font-bold text-indigo-500 min-w-4 inline-block mr-2">খ)</span> {{ $post->b }}
                </p>
                <p class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                    <span class="font-bold text-indigo-500 min-w-4 inline-block mr-2">গ)</span> {{ $post->c }}
                </p>
                <p class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                    <span class="font-bold text-indigo-500 min-w-4 inline-block mr-2">ঘ)</span> {{ $post->d }}
                </p>
            </div>
            
            <!-- Correct Answer Reveal -->
            <div class="mt-8 p-5 bg-yellow-100 text-yellow-800 rounded-xl border-2 border-yellow-400 font-semibold flex items-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-4 text-yellow-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex flex-col sm:flex-row sm:items-baseline">
                    <span class="uppercase text-sm tracking-wider mr-2">Correct Answer:</span> 
                    <span class="text-2xl font-extrabold text-indigo-700">{{ $post->answer }}</span>
                </div>
            </div>
            
            <!-- Detailed Explanation Section (Recommended addition) -->
            @if ($post->explanation ?? false)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-2xl font-bold text-indigo-700 mb-3">Expert Explanation</h3>
                <div class="text-gray-700 leading-relaxed bg-indigo-50 p-4 rounded-lg">
                    {!! $post->explanation !!}
                </div>
            </div>
            @endif

        </div>


        <!-- Discussion/Comments Section -->
        <div class="mt-12">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-3 border-indigo-300">
                Student Discussion ({{ count($comments ?? []) }})
            </h2>

            <!-- New Comment Form -->
            <div class="bg-white p-6 rounded-2xl shadow-lg mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Post a Comment</h3>
                <!-- NOTE: Update the action URL to your actual comment submission route -->
                <form action="{{ url('/comments/' . ($post->id ?? 'q-1234')) }}" method="POST">
                    @csrf
                    <textarea name="body" rows="4" class="w-full p-3 border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition duration-150 resize-none" placeholder="Share your thoughts or ask a follow-up question..." required></textarea>
                    
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-full font-bold shadow-md hover:bg-indigo-700 transition duration-200 transform hover:scale-[1.02]">
                            Submit Comment
                        </button>
                    </div>
                </form>
            </div>

            <!-- List of Existing Comments -->
            <div class="space-y-6">
                <!-- Loop through hypothetical comments -->
                @forelse ($comments ?? [] as $comment)
                <div class="bg-white p-5 rounded-xl shadow border-l-4 border-gray-200">
                    <div class="flex items-center mb-2">
                        <!-- User Avatar/Initial Placeholder -->
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-sm mr-3 flex-shrink-0">
                            {{ substr($comment->user_name ?? 'Student', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $comment->user_name ?? 'Anonymous Student' }}</p>
                            <p class="text-xs text-gray-500">{{ $comment->created_at ?? 'Just now' }}</p>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed pl-11">
                        {{ $comment->body }}
                    </p>
                </div>
                @empty
                <p class="text-center text-gray-500 p-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    No discussion yet. Be the first to post a comment!
                </p>
                @endforelse
            </div>
        </div>

        <!-- MathJax Script Inclusion -->
        <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
        <script>
            // Ensure MathJax renders mathematical content correctly after the DOM loads
            document.addEventListener('DOMContentLoaded', function() {
                MathJax.typesetPromise();
            });
        </script>
    </div>
@endsection