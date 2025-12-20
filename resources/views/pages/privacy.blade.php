@extends('layout')

@section('seo')
@php
    $title = "Privacy Policy - ExamDao";
    $description = "Read ExamDao's privacy policy to understand how we collect, use, and protect your personal data for exam preparation and question bank access.";
    $canonical = url()->current();
@endphp
@endsection

@section('content')
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            {{-- Page Header --}}
            <header class="text-center mb-10">
                <h1 class="text-5xl font-extrabold text-indigo-700">
                    Privacy Policy
                </h1>
                <p class="mt-3 text-lg text-gray-500">
                    Last Updated: December 14, 2025
                </p>
            </header>
            
            <div class="space-y-12 text-gray-700 leading-relaxed">

                {{-- 1. Introduction --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b border-indigo-700 pb-2 text-indigo-700">
                        1. Introduction
                    </h2>
                    <p>
                        Welcome to <span class="font-bold">ExamDao</span>. We respect your privacy and are committed to protecting your personal data. This policy will inform you as to how we look after your personal data when you visit our website (regardless of where you visit it from) and tell you about your privacy rights and how the law protects you.
                    </p>
                    <p class="mt-4">
                        By using the ExamDao website and services (including our Question Bank and Chatbot), you consent to the data practices described in this policy.
                    </p>
                </section>

                {{-- 2. Data We Collect --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b border-indigo-700 pb-2 text-indigo-700">
                        2. The Data We Collect About You
                    </h2>
                    <p>
                        We may collect, use, store, and transfer different kinds of personal data about you which we have grouped together as follows:
                    </p>
                    <ul class="list-disc list-inside mt-3 ml-4 space-y-2">
                        <li><span class="font-bold">Identity Data:</span> Includes first name, last name, username or similar identifier, and student ID (if provided).</li>
                        <li><span class="font-bold">Contact Data:</span> Includes email address, telephone number, and social media handles (like your Facebook URL).</li>
                        <li><span class="font-bold">Profile Data:</span> Includes your exam board (SSC, HSC, Admission, BCS), chapter progress, model test scores, and feedback.</li>
                        <li><span class="font-bold">Technical Data:</span> Includes internet protocol (IP) address, browser type and version, time zone setting, operating system, and platform.</li>
                        <li><span class="font-bold">Usage Data:</span> Includes information about how you use our website, products, and services (e.g., questions viewed, time spent on chatbot).</li>
                    </ul>
                </section>

                {{-- 3. How Data is Collected --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b border-indigo-700 pb-2 text-indigo-700">
                        3. How is Your Personal Data Collected?
                    </h2>
                    <p>We use different methods to collect data from and about you, including through:</p>
                    <ul class="list-disc list-inside mt-3 ml-4 space-y-2">
                        <li><span class="font-bold">Direct Interactions:</span> You may give us your Identity and Contact Data by filling in forms or by corresponding with us by email (<code class="bg-gray-100 px-1 rounded">info@examdao.com</code>), or Facebook messenger (<code class="bg-gray-100 px-1 rounded">facebook.com/examdaobd</code>).</li>
                        <li><span class="font-bold">Automated Technologies (Cookies):</span> As you interact with our website, we may automatically collect Technical and Usage Data about your equipment, browsing actions and patterns using cookies and similar technologies.</li>
                        <li><span class="font-bold">Third Parties:</span> We may receive data from analytics providers such as Google Analytics based outside of Bangladesh.</li>
                    </ul>
                </section>

                {{-- 4. Use of Data --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b border-indigo-700 pb-2 text-indigo-700">
                        4. How We Use Your Personal Data
                    </h2>
                    <p>We will only use your personal data when the law allows us to. We primarily use your data to:</p>
                    <ul class="list-disc list-inside mt-3 ml-4 space-y-2">
                        <li><span class="font-bold">Provide Service Access:</span> To register you as a new user and manage your access to the Question Bank.</li>
                        <li><span class="font-bold">Personalize Learning:</span> To track your progress, suggest relevant chapters, and tailor model tests to your needs (Profile Data).</li>
                        <li><span class="font-bold">Improve Our Product:</span> To analyze usage data and feedback to enhance the quality and range of questions and the Chatbot's accuracy.</li>
                        <li><span class="font-bold">Communicate:</span> To send you service messages, updates, and marketing communications you have opted to receive.</li>
                    </ul>
                </section>
                
                {{-- 5. Cookies and Tracking --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b border-indigo-700 pb-2 text-indigo-700">
                        5. Cookies
                    </h2>
                    <p>
                        Our website uses cookies to distinguish you from other users. This helps us to provide you with a good experience when you browse our website and also allows us to improve our site. You can set your browser to refuse all or some browser cookies, but note that some parts of the website may become inaccessible or not function properly.
                    </p>
                </section>

                {{-- 6. Contact Details --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b border-indigo-700 pb-2 text-indigo-700">
                        6. Contact Details
                    </h2>
                    <p>
                        If you have any questions about this privacy policy or our data practices, please contact us at:
                    </p>
                    <ul class="mt-3 ml-4 space-y-2 font-semibold">
                        <li>Email: <a href="mailto:info@examdao.com" class="text-indigo-700 hover:text-yellow-600 transition-colors">info@examdao.com</a></li>
                        <li>Facebook: <a href="https://facebook.com/examdaobd" target="_blank" class="text-indigo-700 hover:text-yellow-600 transition-colors">facebook.com/examdaobd</a></li>
                    </ul>
                    <p class="mt-4 text-sm italic">
                        We generally respond to all inquiries within 2-3 business days.
                    </p>
                </section>

            </div>

        </div>
    </div>
@endsection