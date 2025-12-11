@extends('layout')

@section('seo')
@php
    $title = "Privacy Policy - ExamDAO";
    $description = "Read ExamDAO's privacy policy to understand how we collect, use, and protect your personal data for exam preparation and question bank access.";
    $canonical = url()->current();
@endphp
@endsection

@section('content')
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            {{-- Page Header --}}
            <header class="text-center mb-10">
                <h1 class="text-5xl font-extrabold text-gray-900" style="color: #4338ca;">
                    Privacy Policy
                </h1>
                <p class="mt-3 text-lg text-gray-500">
                    Last Updated: December 11, 2025
                </p>
            </header>
            
            <div class="space-y-12 text-gray-700 leading-relaxed">

                {{-- 1. Introduction --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b pb-2" style="border-color: #4338ca; color: #4338ca;">
                        1. Introduction
                    </h2>
                    <p>
                        Welcome to **ExamDAO**, operated by [Your Company Name, if applicable]. We respect your privacy and are committed to protecting your personal data. This policy will inform you as to how we look after your personal data when you visit our website (regardless of where you visit it from) and tell you about your privacy rights and how the law protects you.
                    </p>
                    <p class="mt-4">
                        By using the ExamDAO website and services (including our Question Bank and Chatbot), you consent to the data practices described in this policy.
                    </p>
                </section>

                {{-- 2. Data We Collect --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b pb-2" style="border-color: #4338ca; color: #4338ca;">
                        2. The Data We Collect About You
                    </h2>
                    <p>
                        We may collect, use, store, and transfer different kinds of personal data about you which we have grouped together as follows:
                    </p>
                    <ul class="list-disc list-inside mt-3 ml-4 space-y-2">
                        <li><strong>Identity Data:</strong> Includes first name, last name, username or similar identifier, and student ID (if provided).</li>
                        <li><strong>Contact Data:</strong> Includes email address, telephone number, and social media handles (like your Facebook URL).</li>
                        <li><strong>Profile Data:</strong> Includes your exam board (SSC, HSC, Admission, BCS), chapter progress, model test scores, and feedback.</li>
                        <li><strong>Technical Data:</strong> Includes internet protocol (IP) address, browser type and version, time zone setting, operating system, and platform.</li>
                        <li><strong>Usage Data:</strong> Includes information about how you use our website, products, and services (e.g., questions viewed, time spent on chatbot).</li>
                    </ul>
                </section>

                {{-- 3. How Data is Collected --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b pb-2" style="border-color: #4338ca; color: #4338ca;">
                        3. How is Your Personal Data Collected?
                    </h2>
                    <p>We use different methods to collect data from and about you, including through:</p>
                    <ul class="list-disc list-inside mt-3 ml-4 space-y-2">
                        <li><strong>Direct Interactions:</strong> You may give us your Identity and Contact Data by filling in forms or by corresponding with us by email (<code>examdao@gmail.com</code>), or Facebook messenger (<code>facebook.com/examdao</code>).</li>
                        <li><strong>Automated Technologies (Cookies):</strong> As you interact with our website, we may automatically collect Technical and Usage Data about your equipment, browsing actions and patterns using cookies and similar technologies.</li>
                        <li><strong>Third Parties:</strong> We may receive data from analytics providers such as Google Analytics based outside of Bangladesh.</li>
                    </ul>
                </section>

                {{-- 4. Use of Data (Crucial section for an educational site) --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b pb-2" style="border-color: #4338ca; color: #4338ca;">
                        4. How We Use Your Personal Data
                    </h2>
                    <p>We will only use your personal data when the law allows us to. We primarily use your data to:</p>
                    <ul class="list-disc list-inside mt-3 ml-4 space-y-2">
                        <li>**Provide Service Access:** To register you as a new user and manage your access to the Question Bank.</li>
                        <li>**Personalize Learning:** To track your progress, suggest relevant chapters, and tailor model tests to your needs (Profile Data).</li>
                        <li>**Improve Our Product:** To analyze usage data and feedback to enhance the quality and range of questions and the Chatbot's accuracy.</li>
                        <li>**Communicate:** To send you service messages, updates, and marketing communications you have opted to receive.</li>
                    </ul>
                </section>
                
                {{-- 5. Cookies and Tracking --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b pb-2" style="border-color: #4338ca; color: #4338ca;">
                        5. Cookies
                    </h2>
                    <p>
                        Our website uses cookies to distinguish you from other users. This helps us to provide you with a good experience when you browse our website and also allows us to improve our site. You can set your browser to refuse all or some browser cookies, but note that some parts of the website may become inaccessible or not function properly.
                    </p>
                </section>

                {{-- 6. Contact Details --}}
                <section>
                    <h2 class="text-3xl font-bold mb-4 border-b pb-2" style="border-color: #4338ca; color: #4338ca;">
                        6. Contact Details
                    </h2>
                    <p>
                        If you have any questions about this privacy policy or our data practices, please contact us at:
                    </p>
                    <ul class="mt-3 ml-4 space-y-2 font-semibold">
                        <li><strong>Email:</strong> <a href="mailto:examdao@gmail.com" style="color: #4338ca;" class="hover:text-yellow-500">examdao@gmail.com</a></li>
                        <li><strong>Facebook:</strong> <a href="https://facebook.com/examdao" target="_blank" style="color: #4338ca;" class="hover:text-yellow-500">facebook.com/examdao</a></li>
                    </ul>
                    <p class="mt-4 text-sm italic">
                        We generally respond to all inquiries within 2-3 business days.
                    </p>
                </section>

            </div>

        </div>
    </div>
@endsection