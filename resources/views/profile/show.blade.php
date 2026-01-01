@extends('layout')

@section('content')
<div class="bg-slate-50 min-h-screen py-8 px-4">
    <div class="max-w-xl mx-auto space-y-6">

        {{-- 1. Profile Card --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary-50 rounded-full -mr-8 -mt-8 opacity-50"></div>
            
            <div class="flex items-center justify-between relative">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-primary-200">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-900">{{ $user->name }}</h1>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-tight">
                            {{ institution($user->institution->name ?? 'Guest') }} Candidate
                        </p>
                    </div>
                </div>

                {{-- Edit Button --}}
                <a href="{{ route('profile.edit') }}" 
                   class="p-2.5 bg-slate-50 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all duration-200 border border-slate-100"
                   title="Edit Profile">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-8">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <span class="block text-2xl font-black text-primary-600">{{ $user->points }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Study Points</span>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <span class="block text-2xl font-black text-warning-500">{{ $recentAttempts->count() }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Exams Taken</span>
                </div>
            </div>

            {{-- Logout Button inside Profile Card --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full py-3 text-[10px] font-black text-slate-400 hover:text-red-500 uppercase tracking-widest transition-colors border-t border-slate-50 pt-4">
                    Logout from Account
                </button>
            </form>
        </div>

        {{-- Section 2 (Saved Questions) has been removed --}}

        {{-- 2. Recommended Subjects --}}
        <div class="space-y-3">
            <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider px-1">Recommended Subjects</h2>
            <div class="grid grid-cols-2 gap-3">
                @foreach($recommendedSubjects as $subject)
                <a href="{{ route('exam.show', [$user->institution->slug, $subject->slug]) }}" 
                   class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center text-center hover:border-primary-300 transition-all">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mb-2">
                        <x-icons.book class="w-5 h-5"/>
                    </div>
                    <span class="text-xs font-bold text-slate-700">{{ $subject->name }}</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- 3. Recent Performance --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-4">Recent Performance</h2>
            @if($recentAttempts->isEmpty())
                <div class="text-center py-6">
                    <p class="text-sm text-slate-400 italic">No exams taken yet. Ready to start?</p>
                    <a href="{{ route('questions.index') }}" class="text-primary-600 font-bold text-xs underline mt-2 inline-block">Browse Questions</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($recentAttempts as $attempt)
                    <div class="flex items-center justify-between border-b border-slate-50 pb-3 last:border-0">
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $attempt->subject->name }}</p>
                            <p class="text-[10px] text-slate-400">{{ $attempt->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black {{ $attempt->score >= 80 ? 'text-green-500' : 'text-slate-700' }}">
                                {{ $attempt->score }}%
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection