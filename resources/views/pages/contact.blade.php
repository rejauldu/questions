@extends('layout')

@section('seo')
@php
    $title = "Contact Us - Get in Touch with ExamDAO";
    $description = "Have questions about SSC, HSC, Admission, or BCS question banks? Contact ExamDAO via email or Facebook for quick support.";
    $canonical = url()->current();
@endphp
@endsection

@section('content')
    <div class="container mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl lg:text-6xl" style="color: #4338ca;">
                Get in Touch with ExamDAO
            </h1>
            <p class="mt-4 text-xl text-gray-500">
                We're here to help you master your exams. Choose the best way to reach us below.
            </p>
        </div>

        <div class="mt-12 grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
            
            {{-- Email Card --}}
            <div class="pt-6">
                <div class="flow-root bg-white rounded-lg px-6 pb-8 shadow-lg h-full">
                    <div class="-mt-6">
                        <span class="inline-flex items-center justify-center p-3 rounded-md shadow-lg" style="background-color: #4338ca;">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-2 4v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7"></path>
                            </svg>
                        </span>
                        <h3 class="mt-4 text-lg font-medium tracking-tight text-gray-900">Primary Contact (Email)</h3>
                        <p class="mt-2 text-base text-gray-500">For all support, inquiries, and detailed questions. We check this inbox frequently.</p>
                        <p class="mt-2 text-base font-bold text-gray-700">
                            <a href="mailto:examdao@gmail.com" class="text-indigo-600 hover:text-indigo-900" style="color: #4338ca;">
                                examdao@gmail.com
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Facebook Card --}}
            <div class="pt-6">
                <div class="flow-root bg-white rounded-lg px-6 pb-8 shadow-lg h-full">
                    <div class="-mt-6">
                        <span class="inline-flex items-center justify-center p-3 rounded-md shadow-lg" style="background-color: #4338ca;">
                             <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12ZM14.1558 12.0191L14.4984 9.87883H12.7849V8.50854C12.7849 8.02641 13.0131 7.55833 13.7381 7.55833H14.5367V5.7071C14.5367 5.7071 13.8967 5.61719 13.2785 5.61719C12.0006 5.61719 11.1648 6.42578 11.1648 7.97157V9.87883H9.45139V12.0191H11.1648V16.8828H14.1558V12.0191Z"></path>
                            </svg>
                        </span>
                        <h3 class="mt-4 text-lg font-medium tracking-tight text-gray-900">Connect on Facebook</h3>
                        <p class="mt-2 text-base text-gray-500">Send us a direct message on our page for quick chat support.</p>
                        <p class="mt-2 text-base font-bold text-gray-700">
                            <a href="https://facebook.com/examdao" target="_blank" class="text-indigo-600 hover:text-indigo-900" style="color: #4338ca;">
                                facebook.com/examdao
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            
            {{-- Quick Support Card (Replaced Hours/Phone) --}}
            <div class="pt-6">
                <div class="flow-root bg-white rounded-lg px-6 pb-8 shadow-lg h-full">
                    <div class="-mt-6">
                        <span class="inline-flex items-center justify-center p-3 rounded-md shadow-lg" style="background-color: #4338ca;">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.128a11.042 11.042 0 005.428 5.428l1.128-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path>
                            </svg>
                        </span>
                        <h3 class="mt-4 text-lg font-medium tracking-tight text-gray-900">Need Quick Help?</h3>
                        <p class="mt-2 text-base text-gray-500">Email and Facebook are the fastest ways to get support. For urgent matters only, you can try contacting us.</p>
                        <p class="mt-2 text-sm font-bold text-gray-700 italic">
                            (Phone number will be provided upon request via email for urgent issues.)
                        </p>
                    </div>
                </div>
            </div>

        </div>
        
        {{-- Final Instruction Block --}}
        <div class="mt-16 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900" style="color: #4338ca;">
                Why Contact Us?
            </h2>
            <p class="mt-4 text-lg text-gray-600">
                Whether you have questions about specific exam chapters, need help navigating the platform, or have feedback, your success is our priority.
            </p>
        </div>

    </div>
@endsection