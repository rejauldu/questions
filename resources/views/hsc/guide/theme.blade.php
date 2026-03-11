@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Poem Theme Writing</h1>
        <p class="text-gray-500 max-w-md mx-auto mt-2">Learn to extract the core philosophy of a poem in 50 words.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white border rounded-2xl p-6 shadow-sm border-t-4 border-indigo-600">
            <h2 class="font-bold text-indigo-600 mb-4">Top 2026 Suggestions</h2>
            <ul class="space-y-3">
                <li class="flex justify-between text-sm border-b pb-2">
                    <span>Hold Fast to Dreams</span>
                    <span class="text-gray-400">Langston Hughes</span>
                </li>
                <li class="flex justify-between text-sm border-b pb-2">
                    <span>The School Boy</span>
                    <span class="text-gray-400">William Blake</span>
                </li>
                <li class="flex justify-between text-sm border-b pb-2">
                    <span>I Will Arise and Go Now</span>
                    <span class="text-gray-400">W.B. Yeats</span>
                </li>
            </ul>
        </div>

        <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-xl">
            <h2 class="font-bold text-amber-400 mb-4">The Cadre Formula</h2>
            <p class="text-sm text-slate-300 mb-4">"A theme is not what happens in the poem, but what the poem is *about* on a universal level."</p>
            <div class="bg-slate-800 p-4 rounded-lg">
                <p class="text-xs font-mono text-indigo-300">Start with:</p>
                <p class="text-sm italic">"The theme of the poem deals with the importance of..."</p>
            </div>
        </div>
    </div>
    
    <div class="mt-12 text-center">
        <a href="/community" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-full font-bold hover:bg-indigo-700 transition">
            Join Telegram for Daily Theme Practice <x-icons.arrow-right class="w-4 h-4"/>
        </a>
    </div>
</div>
@endsection