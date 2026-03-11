@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-black text-gray-800 uppercase">Informal Email</h1>
            <p class="text-indigo-600 font-bold">Category: Personal Correspondence</p>
        </div>
        <div class="bg-indigo-100 p-3 rounded-lg border border-indigo-200 text-center">
            <span class="block text-2xl font-black text-indigo-700">05</span>
            <span class="text-[10px] uppercase font-bold text-indigo-500">Marks</span>
        </div>
    </div>

    <div class="bg-white border-2 border-dashed border-gray-200 rounded-xl p-6 mb-8 shadow-sm">
        <h2 class="text-sm font-black text-gray-400 uppercase mb-4 tracking-widest">Standard Format (Board Level)</h2>
        <div class="space-y-1 font-mono text-sm border-b pb-4 mb-4">
            <p><span class="font-bold text-indigo-600">From:</span> yourname@gmail.com</p>
            <p><span class="font-bold text-indigo-600">To:</span> friendname@gmail.com</p>
            <p><span class="font-bold text-indigo-600">Sent:</span> {{ date('l, F j, Y; g:i a') }}</p>
            <p><span class="font-bold text-indigo-600">Subject:</span> Describing the prize-giving ceremony of our college.</p>
        </div>
        <div class="prose max-w-none text-gray-700 leading-relaxed">
            Dear [Friend's Name], <br>
            Take my love first. I hope you are doing well...
        </div>
    </div>

    <div class="bg-indigo-600 rounded-xl p-6 text-white shadow-lg">
        <h3 class="font-bold mb-3 flex items-center gap-2">
            <x-icons.bcs-cadre class="w-5 h-5"/> Curated by BCS Cadre Officers
        </h3>
        <ul class="text-sm space-y-2 opacity-90">
            <li>• Keep the body concise (within 100-120 words).</li>
            <li>• Avoid overly formal language; it's an informal email.</li>
            <li>• Ensure the Subject line is relevant and catchy.</li>
        </ul>
    </div>
</div>
@endsection