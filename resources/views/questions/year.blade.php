@extends('layout')

@section('seo')
@php
    // $displayName আমরা কন্ট্রোলার থেকে পাস করেছি (যেমন: Bangla)
    $title = $displayName . ' Questions by Year - ' . institution($institution->name);
    $description = 'Browse ' . $displayName . ' exam questions by year for ' . institution($institution->name);
    $canonical = url()->current();
@endphp
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-4 md:py-8">
    <div class="max-w-6xl mx-auto px-4">
        
        {{-- Breadcrumb Navigation --}}
        <nav class="flex items-center gap-2 text-xs mb-4 text-gray-500 overflow-x-auto whitespace-nowrap pb-2">
            <a href="{{ route('exam.show') }}" class="hover:text-primary-600">Exams</a>
            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
            <a href="{{ route('exam.show', $institution->slug) }}" class="hover:text-primary-600">{{ institution($institution->name) }}</a>
            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
            {{-- সাবজেক্ট স্লাগ পেজে ফিরে যাওয়ার লিংক --}}
            <a href="{{ route('exam.show', [$institution->slug, $subjectSlug]) }}" class="hover:text-primary-600">{{ $displayName }}</a>
        </nav>

        {{-- Header Section --}}
        <div class="mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-primary-600 rounded-full"></span>
                Select Year
            </h1>
            <p class="text-xs text-gray-500 mt-1">Showing available exam years for {{ $displayName }}</p>
        </div>

        {{-- Years Grid --}}
        <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-2 md:gap-3 mb-10">
            @foreach($years as $yr)
                {{-- এখানে $subjectSlug ব্যবহার করা হয়েছে যা জেনেরিক বা স্পেসিফিক উভয়ই হতে পারে --}}
                <a href="{{ route('exam.show', [$institution->slug, $subjectSlug, $yr]) }}" 
                   class="group flex flex-col items-center justify-center p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:border-primary-500 hover:ring-1 hover:ring-primary-500 transition-all duration-200">
                    
                    <div class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-600 font-bold text-[10px] group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>

                    <span class="text-xs font-bold text-gray-700 text-center">
                        {{ $yr }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Content Divider --}}
        <div class="flex items-center gap-4 mb-6">
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400 whitespace-nowrap">
                {{ $displayName }} Feed
            </span>
            <div class="w-full h-px bg-gray-200"></div>
        </div>

        {{-- Questions Feed --}}
        <div class="space-y-4">
            @if($posts->count())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-1 md:p-4">
                    @include('partials.post-loop')
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-300">
                    <p class="text-gray-400 text-sm">No questions found for {{ $displayName }} yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection