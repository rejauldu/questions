@extends('layout')

@section('seo')
@php
    $title = "ExamDao - Smart Question Bank for SSC, HSC, Admission, NU & BCS";
    $description = "Prepare smarter with ExamDao — access SSC, HSC, Admission, NU, and BCS question banks with chapter-wise practice sets, past papers, model tests, and expert solutions to boost your exam performance.";
    $image = url('/images/og-home.webp');   // Recommended: 1200x630 optimized WebP banner
    $canonical = url()->current();
@endphp
@endsection

@section('content')
    @include('home.hero')
    @include('home.feature')
    @include('home.content')
    @include('home.planner')
    @include('home.cta')
@endsection