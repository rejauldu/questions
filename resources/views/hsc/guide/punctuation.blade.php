@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-2 py-4 sm:px-4 sm:py-8 bg-white min-h-screen text-slate-700">
    <div class="mb-6 px-1">
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-indigo-600 mb-1">
            <span>HSC English 2nd</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-400">Question 9</span>
        </nav>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Punctuation Marks</h1>
    </div>

    <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 mb-8 rounded-r-xl shadow-sm">
        <h3 class="text-indigo-900 font-black text-sm uppercase mb-1">কৌশল (General Techniques):</h3>
        <ul class="text-indigo-900 text-sm sm:text-base space-y-2 list-disc pl-5 leading-snug">
            <li>বক্তব্য শেষ হলে Full Stop (.), প্রশ্ন থাকলে Question Mark (?) এবং বিরতির জন্য Comma (,) ব্যবহার করো।</li>
            <li>Direct Speech-এর ক্ষেত্রে উদ্ধৃতি চিহ্ন বা Quotation Marks (" ") সঠিকভাবে বসাতে ভুলো না।</li>
        </ul>
    </div>

    <section class="mb-10 px-1 space-y-4">
        <h2 class="text-slate-800 font-bold text-sm sm:text-base mb-2">9. Use punctuation marks where necessary in the following text:</h2>
        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-inner leading-relaxed text-slate-800 text-sm sm:text-lg italic">
            the teacher said to the boy do you think that honesty is the best policy yes sir i think so said the boy then learn to be honest from your boyhood thank you sir said the boy may allah grant you a long life said the teacher
        </div>
    </section>

    

    <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <h2 class="mb-4 italic border-b-2 border-indigo-400 pb-1 inline-block text-lg">Ans to the Q. no-9</h2>
        <div class="text-sm sm:text-base leading-loose font-medium mt-2">
            The teacher said to the boy, "Do you think that honesty is the best policy?" "Yes, sir, I think so," said the boy. "Then learn to be honest from your boyhood." "Thank you, sir," said the boy. "May Allah grant you a long life," said the teacher.
        </div>
    </div>
</div>
@endsection