@extends('layout')

@section('seo')
@php
    $title = "Terms of Service - ExamDAO";
    $description = "Read ExamDAO's Terms of Service, covering user registration, platform usage, intellectual property rights over question bank content, and acceptable behavior.";
    $canonical = url()->current();
@endphp
@endsection

@section('content')
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            {{-- Page Header --}}
            <header class="text-center mb-10">
                <h1 class="text-5xl font-extrabold text-gray-900" style="color: #4338ca;">
                    Terms of Service
                </h1>
                <p class="mt-3 text-lg text-gray-500">
                    Effective Date: December 11, 2025
                </p>
            </header>
            
            <div class="space-y-12 text-gray-700 leading-relaxed">

                {{-- 1. Acceptance of Terms --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b pb-2" style="border-color: #4338ca; color: #4338ca;">
                        1. Agreement to Terms
                    </h2>
                    <p>
                        These Terms of Service (the "Terms") constitute a legally binding agreement made between you, whether personally or on behalf of an entity ("you"), and **ExamDAO** concerning your access to and use of the <a href="{{ url('/') }}" class="font-semibold" style="color: #4338ca;">ExamDAO.com</a> website as well as any other media form, media channel, mobile website or mobile application related, linked, or otherwise connected thereto (collectively, the "Site").
                    </p>
                    <p class="mt-4">
                        You agree that by accessing the Site, you have read, understood, and agreed to be bound by all of these Terms of Service. If you do not agree with all of these Terms of Service, then you are expressly prohibited from using the Site and you must discontinue use immediately.
                    </p>
                </section>

                {{-- 2. User Registration and Account Security --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b pb-2" style="border-color: #4338ca; color: #4338ca;">
                        2. User Registration
                    </h2>
                    <p>
                        To use some of the services on the Site, you may be required to register with ExamDAO. You agree to keep your password confidential and will be responsible for all use of your account and password. We reserve the right to remove, reclaim, or change a username you select if we determine, in our sole discretion, that such username is inappropriate, obscene, or otherwise objectionable.
                    </p>
                    <p class="mt-3">
                        **Age Restriction:** Use of the Site is limited to users who are at least 13 years of age. Users under the age of 18 should review these terms with a parent or guardian.
                    </p>
                </section>

                {{-- 3. Intellectual Property Rights (Crucial for a content site) --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b pb-2" style="border-color: #4338ca; color: #4338ca;">
                        3. Intellectual Property Rights
                    </h2>
                    <p>
                        Unless otherwise indicated, the Site is our proprietary property. All questions, solutions, model tests, code, design, features, and functionality (the "Content") are owned or controlled by us and are protected by copyright and intellectual property laws.
                    </p>
                    <p class="mt-3 font-semibold">
                        Prohibited Actions:
                    </p>
                    <ul class="list-disc list-inside mt-2 ml-4 space-y-2">
                        <li>You may not copy, reproduce, distribute, sell, or otherwise exploit the Content for any commercial purpose without our express written permission.</li>
                        <li>Systematic retrieval of data or other content from the Site to create or compile, directly or indirectly, a collection, compilation, database, or directory is prohibited.</li>
                        <li>Sharing or selling your account credentials to a third party is strictly prohibited and will result in immediate account termination.</li>
                    </ul>
                </section>

                {{-- 4. Prohibited Activities --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b pb-2" style="border-color: #4338ca; color: #4338ca;">
                        4. Prohibited Activities
                    </h2>
                    <p>
                        You may not access or use the Site for any purpose other than that for which we make the Site available. Prohibited activities include, but are not limited to:
                    </p>
                    <ul class="list-disc list-inside mt-3 ml-4 space-y-2">
                        <li>Using the Chatbot to generate harmful, illegal, or harassing content.</li>
                        <li>Attempting to circumvent any measures of the Site designed to prevent or restrict access to the Site, or any portion of the Site.</li>
                        <li>Making any unauthorized use of the services, including collecting usernames and/or email addresses of users by electronic or other means for the purpose of sending unsolicited email.</li>
                    </ul>
                </section>

                {{-- 5. Contact Information --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b pb-2" style="border-color: #4338ca; color: #4338ca;">
                        5. Governing Law and Contact
                    </h2>
                    <p>
                        These Terms shall be governed by and defined by the laws of **Bangladesh**. Any dispute arising under them shall be subject to the exclusive jurisdiction of the courts of Bangladesh.
                    </p>
                    <p class="mt-3">
                        For any questions regarding these Terms, please contact us at:
                    </p>
                    <ul class="mt-3 ml-4 space-y-2 font-semibold">
                        <li><strong>Email:</strong> <a href="mailto:examdao@gmail.com" style="color: #4338ca;" class="hover:text-yellow-500">examdao@gmail.com</a></li>
                    </ul>
                </section>

            </div>

        </div>
    </div>
@endsection