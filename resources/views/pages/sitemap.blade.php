@extends('layout')

@section('seo')
@php
    $title = "ExamDAO Site Map - All Content Index";
    $description = "A complete index of all pages and major exam categories on ExamDAO to help you find content quickly and efficiently.";
    $canonical = url()->current();
@endphp
@endsection

@section('content')
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            <header class="text-center mb-10">
                <h1 class="text-5xl font-extrabold text-gray-900" style="color: #4338ca;">
                    Site Index (Sitemap)
                </h1>
                <p class="mt-3 text-lg text-gray-500">
                    A comprehensive guide to all the resources available on ExamDAO.
                </p>
            </header>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                {{-- Column 1: Core Navigation & Authentication --}}
                <div>
                    <h2 class="text-2xl font-bold mb-4 border-b pb-2 text-indigo-700">
                        Primary Navigation
                    </h2>
                    <ul class="space-y-3 text-lg">
                        <li><a href="{{ url('/') }}" class="text-gray-700 hover:text-yellow-500 transition">Homepage</a></li>
                        <li><a href="{{ url('/questions') }}" class="text-gray-700 hover:text-yellow-500 transition">Questions Index / Search</a></li>
                        <li><a href="{{ url('/chatbot') }}" class="text-gray-700 hover:text-yellow-500 transition">Exam Chatbot</a></li>
                        <li><a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-yellow-500 transition">User Dashboard</a></li>
                        <li><a href="{{ url('/login') }}" class="text-gray-700 hover:text-yellow-500 transition">Login</a></li>
                        <li><a href="{{ url('/register') }}" class="text-gray-700 hover:text-yellow-500 transition">Register</a></li>
                    </ul>
                </div>

                {{-- Column 2: Exam Categories (Dynamic Content) --}}
                <div>
                    <h2 class="text-2xl font-bold mb-4 border-b pb-2 text-indigo-700">
                        Exam Boards & Categories
                    </h2>
                    <ul class="space-y-3 text-lg">
                        <li><a href="{{ route('questions.index', ['board' => 'ssc']) }}" class="text-gray-700 hover:text-yellow-500 transition">SSC (Secondary School)</a></li>
                        <li><a href="{{ route('questions.index', ['board' => 'hsc']) }}" class="text-gray-700 hover:text-yellow-500 transition">HSC (Higher Secondary)</a></li>
                        <li><a href="{{ route('questions.index', ['board' => 'university-admission']) }}" class="text-gray-700 hover:text-yellow-500 transition">University Admission Tests</a></li>
                        <li><a href="{{ route('questions.index', ['board' => 'bcs']) }}" class="text-gray-700 hover:text-yellow-500 transition">BCS & Professional Exams</a></li>
                        {{-- Add dynamic links to subject/chapter lists here later --}}
                        <li class="pt-2 italic text-sm text-gray-500">
                            (More detailed subject/chapter links would be dynamically listed here)
                        </li>
                    </ul>
                </div>

                {{-- Column 3: Legal & Information --}}
                <div class="md:col-span-2 mt-8">
                    <h2 class="text-2xl font-bold mb-4 border-b pb-2 text-indigo-700">
                        Legal & Information
                    </h2>
                    <div class="grid grid-cols-2 gap-y-3">
                        <a href="{{ url('/about') }}" class="text-gray-700 hover:text-yellow-500 transition">About Us</a>
                        <a href="{{ route('contact') }}" class="text-gray-700 hover:text-yellow-500 transition">Contact Support</a>
                        <a href="{{ url('/privacy') }}" class="text-gray-700 hover:text-yellow-500 transition">Privacy Policy</a>
                        <a href="{{ url('/terms') }}" class="text-gray-700 hover:text-yellow-500 transition">Terms of Service</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection