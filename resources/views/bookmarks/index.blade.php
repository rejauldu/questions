@extends('layout')

@section('content')
<div class="bg-slate-50 min-h-screen py-8 px-4">
    <div class="max-w-2xl mx-auto">
        
        {{-- Header --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('profile.show') }}" class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center border border-slate-200 text-slate-400 hover:text-primary-600 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 leading-none">Saved Questions</h1>
                <p class="text-xs font-bold text-slate-400 uppercase mt-1 tracking-wider">Your Archive</p>
            </div>
        </div>

        {{-- Now this works perfectly because $posts contains Post models --}}
        @include('partials.post-loop', ['posts' => $posts])

    </div>
</div>
@endsection