@extends('layout')

@section('content')
    {{-- Centering Wrapper --}}
    <div class="min-h-[80vh] grid place-items-center px-4 py-8">
        
        {{-- Login Card --}}
        <div class="w-full max-w-md bg-white p-6 sm:p-8 rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/60">
            
            {{-- Card Header --}}
            <div class="text-center mb-8">
                <h1 class="text-2xl font-extrabold text-slate-900">Welcome Back</h1>
                <p class="text-sm text-slate-500 mt-2">Log in to access your dashboard and AI tools.</p>
            </div>

            {{-- SEO & Title --}}
            <x-slot name="title">Log in</x-slot>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Login (Email or Phone) --}}
                <div>
                    <label for="login" class="block font-bold text-xs uppercase tracking-widest text-slate-500 mb-1">Phone or Email</label>
                    <input 
                        id="login" 
                        type="text" 
                        name="login" 
                        value="{{ old('login') }}" 
                        class="mt-1 block w-full border-slate-200 focus:border-primary-500 focus:ring-primary-500 rounded-xl shadow-sm py-3 px-4 transition-all"
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="e.g. 01712345678"
                    >
                    @error('login')
                        <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block font-bold text-xs uppercase tracking-widest text-slate-500 mb-1">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        class="mt-1 block w-full border-slate-200 focus:border-primary-500 focus:ring-primary-500 rounded-xl shadow-sm py-3 px-4 transition-all"
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember & Forgot (Optional) --}}
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="flex items-center cursor-pointer">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember" 
                            class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500"
                        >
                        <span class="ms-2 text-sm text-slate-600">Remember me</span>
                    </label>
                </div>

                {{-- Login Button --}}
                <div class="pt-2">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-primary-800 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-primary-700 active:scale-[0.98] transition-all duration-150 shadow-lg shadow-primary-200">
                        Log in
                    </button>
                </div>

                {{-- Create Account Link --}}
                <div class="text-center pt-4 border-t border-slate-50">
                    <p class="text-sm text-slate-500">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-bold text-primary-600 hover:text-primary-500 transition-colors underline decoration-primary-200 underline-offset-4">
                            Create an account
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection