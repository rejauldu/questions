@extends('layout')

@section('seo')
@php
    $title = "About ExamDao - Our Mission for Free Education";
    $description = "Learn the story behind ExamDao, a dedicated project by an individual founder committed to providing the most comprehensive, free question bank for SSC, HSC, Admission, and BCS exams in Bangladesh.";
    $canonical = url()->current();
@endphp
@endsection

@section('content')
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            {{-- Page Header --}}
            <header class="text-center mb-10">
                <h1 class="text-5xl font-extrabold text-gray-900" style="color: #4338ca;">
                    Our Story: Built for You
                </h1>
                <p class="mt-3 text-xl text-gray-500">
                    Empowering students with accessible knowledge, one question at a time.
                </p>
            </header>
            
            <div class="space-y-16 text-gray-700 leading-relaxed">

                {{-- 1. The ExamDao Mission (Focus on Free Access & Quality) --}}
                <section class="bg-indigo-50 p-8 rounded-xl shadow-lg">
                    <h2 class="text-3xl font-bold mb-4" style="color: #4338ca;">
                        Our Mission: Free & Focused
                    </h2>
                    <p class="text-lg">
                        <b>ExamDao</b> was founded on a simple, powerful belief: <b>financial barriers should never block academic success.</b> We are committed to providing the most comprehensive and verified question bank for SSC, HSC, University Admission, and BCS exams—<b>completely free of charge.</b>
                    </p>
                    <p class="mt-4">
                        We know exam preparation is tough. That’s why we tirelessly collect, categorize, and verify past questions and solutions, turning disorganized information into a smart, accessible resource powered by a helpful Chatbot.
                    </p>
                    <div class="mt-6 p-4 border-l-4 border-yellow-400 bg-white shadow-sm">
                        <p class="italic text-gray-600">
                            "Our mission is to democratize test prep. Your success is the only currency we seek."
                        </p>
                    </div>
                </section>

                {{-- 2. Meet the Founder (Personal Touch for Trust) --}}
                <section class="grid md:grid-cols-3 gap-8 items-start">
                    <div class="md:col-span-1 text-center">
                        {{-- Placeholder for Founder Image --}}
                        <div class="w-48 h-48 mx-auto rounded-full bg-gray-200 flex items-center justify-center border-4 border-yellow-400 overflow-hidden">
                             
                        </div>
                        <h3 class="mt-4 text-xl font-bold text-gray-900">
                            Rejaul Karim
                        </h3>
                        <p class="text-sm text-indigo-600">
                            Founder & Developer
                        </p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <h2 class="text-3xl font-bold mb-4" style="color: #4338ca;">
                            The Individual Behind ExamDao
                        </h2>
                        <p class="mt-4">
                            Unlike large corporations, ExamDao is a project born out of personal frustration with the lack of organized, free educational tools available to students in Bangladesh. I started this site [mention year/context] as a solo developer with the goal of creating the exact resource I wish I had while preparing for my own [mention SSC, HSC, or admission test].
                        </p>
                        <p class="mt-3">
                            Every line of code, every question verified, and every feature of the Chatbot is personally overseen. This is a labor of love, maintained through a commitment to quality and supported entirely by <b>Google Ads</b> to ensure it remains a free resource for everyone.
                        </p>
                    </div>
                </section>

                {{-- 3. How We Stay Free (Transparency for Ad Monetization) --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b pb-2" style="border-color: #4338ca; color: #4338ca;">
                        How ExamDao Remains Free
                    </h2>
                    <p>
                        We are currently sustained solely by advertising revenue. This model allows us to offer <b>100% free access</b> to all question banks and features.
                    </p>
                    <ul class="list-disc list-inside mt-3 ml-4 space-y-2 text-lg">
                        <li><b>Support through Views:</b> By simply using the site and viewing our content, you support our mission.</li>
                        <li><b>Non-Intrusive Ads:</b> We strive to ensure advertisements are non-disruptive and relevant, keeping your study experience smooth.</li>
                        <li><b>Future Plans:</b> Should we ever introduce non-ad revenue streams (like donations), we promise to keep the core question bank free.</li>
                    </ul>
                </section>
                
                {{-- 4. Get Involved & Contact --}}
                <section class="text-center pt-8">
                    <h2 class="text-3xl font-bold mb-4" style="color: #4338ca;">
                        Have a Question or Feedback?
                    </h2>
                    <p class="text-lg mb-6">
                        We are always listening. If you find an error, want to suggest a new feature, or just want to say hello, reach out!
                    </p>
                    <a href="{{ route('contact') }}" 
                       class="inline-block bg-yellow-400 text-indigo-800 px-8 py-3 rounded-full text-lg font-bold shadow-xl hover:bg-yellow-300 transition duration-300 transform hover:scale-105">
                       Contact the Founder
                    </a>
                </section>

            </div>
        </div>
    </div>
@endsection