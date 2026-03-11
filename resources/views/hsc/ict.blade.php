@extends('layout')

@section('seo')
@php
    $title = "HSC ICT নম্বর বিভাজন ২০২৬ (CQ, MCQ ও ব্যবহারিক)";
    $description = "এইচএসসি আইসিটি পরীক্ষার সর্বশেষ নম্বর বিভাজন ২০২৬। সৃজনশীল (৫০), বহুনির্বাচনি (২৫) এবং ব্যবহারিক (২৫) পরীক্ষার বিস্তারিত তথ্য ও অধ্যায়ভিত্তিক সাজেশন।";
    $canonical = url()->current();
@endphp
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen text-gray-900 antialiased pb-10">

    <div class="max-w-2xl mx-auto px-3">
        
        {{-- Header Section --}}
        <header class="py-6 text-center">
            <h1 class="text-2xl font-black uppercase tracking-tighter text-gray-800">
                HSC ICT <span class="text-indigo-600">নম্বর বিভাজন</span>
            </h1>
            <div class="flex items-center justify-center gap-2 mt-1">
                <span class="h-[1px] w-8 bg-indigo-200"></span>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Syllabus {{ date('Y') }}</p>
                <span class="h-[1px] w-8 bg-indigo-200"></span>
            </div>
        </header>

        {{-- 1. Creative Questions (CQ) Section --}}
        <div id="cq-section" class="mb-8 bg-white rounded-2xl shadow-sm border border-indigo-100 overflow-hidden">
            <div class="bg-indigo-600 px-4 py-3 flex justify-between items-center">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-tight">সৃজনশীল প্রশ্ন (CQ)</h2>
                    <p class="text-indigo-200 text-[9px] font-medium uppercase">৮টি থেকে ৫টির উত্তর দিতে হবে</p>
                </div>
                <span class="text-xs font-black text-indigo-900 bg-indigo-100 px-2 py-1 rounded">৫০</span>
            </div>
            
            <div class="p-4">
                <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-3">প্রতিটি প্রশ্নের মান বণ্টন (১০ নম্বর)</h3>
                
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <div class="bg-gray-50 p-2 rounded-xl border border-gray-100 text-center">
                        <span class="text-[10px] font-bold text-gray-400 block uppercase">ক - জ্ঞানমূলক</span>
                        <span class="text-sm font-black text-slate-700">০১</span>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-xl border border-gray-100 text-center">
                        <span class="text-[10px] font-bold text-gray-400 block uppercase">খ - অনুধাবন</span>
                        <span class="text-sm font-black text-slate-700">০২</span>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-xl border border-gray-100 text-center">
                        <span class="text-[10px] font-bold text-gray-400 block uppercase">গ - প্রয়োগ</span>
                        <span class="text-sm font-black text-slate-700">০৩</span>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-xl border border-gray-100 text-center">
                        <span class="text-[10px] font-bold text-gray-400 block uppercase">ঘ - উচ্চতর দক্ষতা</span>
                        <span class="text-sm font-black text-slate-700">০৪</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-xs text-gray-500 leading-relaxed bg-indigo-50/50 p-3 rounded-lg border-l-4 border-indigo-400">
                        <span class="font-bold text-indigo-700 font-bengali">নোট:</span> প্রতিটি অধ্যায় থেকে কমপক্ষে ১টি প্রশ্ন থাকবে। বিশেষ করে ৩য় ও ৫ম অধ্যায় থেকে ২টি করে বড় প্রশ্ন আসার সম্ভাবনা বেশি।
                    </p>
                    <a href="{{ route('hsc.show', ['subject' => 'ict', 'year' => '2025', 'category' => 'CQ']) }}" class="flex items-center justify-center gap-2 w-full py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">
                        CQ সাজেশন্স দেখুন <x-icons.chevron-right class="w-3 h-3" />
                    </a>
                </div>
            </div>
        </div>

        {{-- 2. Multiple Choice (MCQ) Section --}}
        <div id="mcq-section" class="mb-8 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-800 px-4 py-3 flex justify-between items-center">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-tight">বহুনির্বাচনি প্রশ্ন (MCQ)</h2>
                    <p class="text-slate-400 text-[9px] font-medium uppercase">২৫টি প্রশ্নের সবগুলোর উত্তর দিতে হবে</p>
                </div>
                <span class="text-xs font-black text-slate-800 bg-slate-100 px-2 py-1 rounded">২৫</span>
            </div>

            <div class="p-4">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">অধ্যায়ভিত্তিক সম্ভাব্য প্রশ্ন সংখ্যা</h3>
                
                <div class="space-y-0">
                    @php
                        $ictMcq = [
                            ['ch' => '১ম', 'name' => 'তথ্য ও যোগাযোগ প্রযুক্তি: বিশ্ব ও বাংলাদেশ', 'marks' => '৩-৪'],
                            ['ch' => '২য়', 'name' => 'কমিউনিকেশন সিস্টেমস ও নেটওয়ার্কিং', 'marks' => '৩-৪'],
                            ['ch' => '৩য়', 'name' => 'সংখ্যা পদ্ধতি ও ডিজিটাল ডিভাইস', 'marks' => '৫-৬'],
                            ['ch' => '৪র্থ', 'name' => 'ওয়েব ডিজাইন ও HTML', 'marks' => '৩-৪'],
                            ['ch' => '৫ম', 'name' => 'প্রোগ্রামিং ভাষা', 'marks' => '৪-৫'],
                            ['ch' => '৬ষ্ঠ', 'name' => 'ডেটাবেজ ম্যানেজমেন্ট সিস্টেম', 'marks' => '২-৩'],
                        ];
                    @endphp

                    @foreach($ictMcq as $row)
                    <div class="py-3 border-b border-gray-50 last:border-0 flex justify-between items-center">
                        <div class="flex gap-3 items-center">
                            <span class="text-[10px] font-black bg-slate-100 text-slate-500 w-8 h-8 rounded-lg flex items-center justify-center shrink-0">{{ $row['ch'] }}</span>
                            <span class="text-xs font-bold text-gray-700 leading-tight">{{ $row['name'] }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-mono font-black text-slate-800">{{ $row['marks'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                {{-- Updated Route for 2025 --}}
                <a href="{{ route('hsc.show', ['subject' => 'ict', 'year' => '2025', 'category' => 'MCQ']) }}" class="mt-4 flex items-center justify-center gap-2 w-full py-3 border-2 border-slate-800 text-slate-800 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 hover:text-white transition-all">
                    ২০২৫ MCQ প্র্যাকটিস শুরু করুন
                </a>
            </div>
        </div>

        {{-- 3. Practical Section (Updated from Image) --}}
        <div id="practical-section" class="mb-6 bg-white rounded-2xl shadow-sm border border-green-100 overflow-hidden">
            <div class="bg-green-600 px-4 py-3 flex justify-between items-center">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-tight">ব্যবহারিক (Practical)</h2>
                    <p class="text-green-200 text-[9px] font-medium uppercase">Short Syllabus অনুযায়ী সমাধান</p>
                </div>
                <span class="text-xs font-black text-green-900 bg-green-100 px-2 py-1 rounded">২৫</span>
            </div>

            <div class="p-4">
                <h3 class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-3">ব্যবহারিক সিলেবাস তালিকা</h3>
                
                <div class="space-y-2">
                    @php
                        $practicals = [
                            'HTML-এ ফরম্যাটিং ট্যাগের ব্যবহার',
                            'প্যারাগ্রাফ, হেডিং, কালার এবং বিন্যাস',
                            'বুলেট এবং নাম্বারিং লিস্ট এর ব্যবহার',
                            'ছবি সংযোজন এবং Hyperlink এর ব্যবহার',
                            'HTML-এ Table তৈরিকরণ এবং ডাটা প্রবেশ',
                            'HTML-এ ফ্রেমের ব্যবহার',
                            'ইনপুট আউটপুট স্টেটমেন্ট (C Program)',
                            'কন্ডিশনাল স্টেটমেন্ট (C Program)',
                            'লুপ স্টেটমেন্ট (C Program)',
                            'অ্যারে (C Program)',
                            'ফাংশন (C Program)'
                        ];
                    @endphp

                    @foreach($practicals as $index => $task)
                    <div class="flex items-center justify-between p-3 bg-green-50/50 rounded-xl border border-green-100/30 group">
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold text-green-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}.</span>
                            <span class="text-xs font-bold text-gray-700 leading-tight font-bengali">{{ $task }}</span>
                        </div>
                        <a href="/hsc/ict/practical/{{ $index + 1 }}" class="text-[10px] font-black text-green-600 uppercase tracking-tighter hover:underline shrink-0 ml-2">
                            সমাধান →
                        </a>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 p-3 bg-amber-50 rounded-xl border border-amber-100 flex gap-3">
                    <span class="text-lg">💡</span>
                    <p class="text-[10px] text-amber-800 font-medium leading-relaxed font-bengali">
                        <span class="font-black">প্রস্তুতি টিপস:</span> ব্যবহারিক পরীক্ষায় সাধারণত ৪র্থ অধ্যায় (HTML) এবং ৫ম অধ্যায়ের প্রোগ্রামিং ভাষা থেকে সবচেয়ে বেশি প্রশ্ন থাকে।
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection