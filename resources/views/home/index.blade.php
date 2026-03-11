@extends('layout')

@section('seo')
@php
    $title = "ExamDao - BCS Cadre Curated Question Bank for SSC, HSC & BCS";
    $description = "Prepare smarter with ExamDao. Access SSC, HSC, BCS, and Admission question banks with chapter-wise practice, model tests, and expert solutions.";
    $image = url('/images/og-home.webp');   // Recommended: 1200x630 optimized WebP banner
    $canonical = url()->current();
@endphp
@endsection

@section('content')
    @include('home.hero')
    @include('home.dynamic-home')
    @include('home.subject-clusters')
    @include('home.planner')
    @include('home.cta')
    @include('home.faq')
@endsection