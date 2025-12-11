@extends('layout')

@section('seo')
@php
    $title = "404 - Page Not Found | ExamDAO";
    $description = "The page you were looking for doesn't exist on ExamDAO. Use our links to find past exam questions, the chatbot, or return to the homepage.";
    $canonical = url()->current();
@endphp
@endsection

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[70vh] text-center px-4 sm:px-6 lg:px-8">
        
        {{-- Large Error Code --}}
        <p class="text-9xl font-extrabold text-indigo-700 opacity-20 select-none tracking-tight">
            404
        </p>

        {{-- Heading and Message --}}
        <h1 class="mt-4 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
            Whoops! Lost in the Question Bank?
        </h1>
        
        <p class="mt-6 text-xl text-gray-600 max-w-xl">
            We couldn't find the page you were looking for. It might have been moved, deleted, or you might have typed the address incorrectly.
        </p>
        
        {{-- Navigation Options --}}
        <div class="mt-10 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
            
            {{-- Home Button --}}
            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-lg text-indigo-900 bg-yellow-400 hover:bg-yellow-300 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                Go Back Home
            </a>
            
            {{-- Questions Button --}}
            <a href="{{ url('/questions') }}"
               class="inline-flex items-center justify-center px-6 py-3 border border-indigo-200 text-base font-medium rounded-full shadow-sm text-indigo-700 bg-white hover:bg-indigo-50 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Browse Questions
            </a>
        </div>
        
        {{-- Suggestion to use Search --}}
        <p class="mt-8 text-sm text-gray-500">
            Alternatively, try using the search bar in the header to find what you need.
        </p>

    </div>
@endsection