@extends('layout')

@section('seo')
@php
    $title = "{$year} {$subject->name} Questions - " . institution($institution->name);
    $description = "Access previous year questions for {$year} {$subject->name} at " . institution($institution->name);
    $canonical = url()->current();
@endphp
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-4 md:py-8">
    <div class="max-w-6xl mx-auto px-4">
        
        {{-- Full Breadcrumb Navigation --}}
        <nav class="flex items-center gap-2 text-xs mb-4 text-gray-500 overflow-x-auto whitespace-nowrap pb-2">
            <a href="{{ route('exam.show') }}" class="hover:text-primary-600">Exams</a>
            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
            <a href="{{ route('exam.show', $institution->slug) }}" class="hover:text-primary-600">{{ institution($institution->name) }}</a>
            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
            <a href="{{ route('exam.show', [$institution->slug, $subject->slug]) }}" class="hover:text-primary-600">{{ $subject->name }}</a>
            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
            <span class="font-semibold text-gray-800">{{ $year }}</span>
        </nav>

        {{-- Active Filter Header --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-4 md:p-6 mb-8 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900">
                        {{ $subject->name }} <span class="text-primary-600">— {{ $year }}</span>
                    </h1>
                    <p class="text-sm text-gray-500 mt-1 uppercase tracking-wider font-medium">
                        {{ institution($institution->name) }}
                    </p>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-primary-50 text-primary-700 text-xs font-bold rounded-full border border-primary-100">
                        {{ $posts->total() }} Questions Available
                    </span>
                </div>
            </div>
        </div>

        {{-- Main Content Section --}}
        <div class="flex flex-col gap-6">
            {{-- Content Divider --}}
            <div class="flex items-center gap-4">
                <span class="text-xs font-bold uppercase tracking-widest text-gray-400 whitespace-nowrap">Question Bank</span>
                <div class="w-full h-px bg-gray-200"></div>
            </div>

            {{-- Questions Feed --}}
            <div class="space-y-4">
                @if($posts->count())
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-1 md:p-4">
                        @include('partials.post-loop')
                    </div>
                @else
                    <div class="text-center py-16 bg-white rounded-3xl border border-dashed border-gray-300">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-900 font-bold">No Questions Found</h3>
                        <p class="text-gray-400 text-sm mt-1">We haven't uploaded the questions for this year yet.</p>
                        <a href="{{ route('exam.show', [$institution->slug, $subject->slug]) }}" class="mt-4 inline-block text-primary-600 font-bold text-sm hover:underline">
                            Try another year →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection