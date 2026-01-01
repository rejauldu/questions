@extends('layout')

@section('content')
    {{-- Centering Wrapper --}}
    <div class="min-h-[80vh] grid place-items-center px-4 py-8">
        
        {{-- Register Card --}}
        <div class="w-full max-w-md bg-white p-6 sm:p-8 rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/60">
            
            {{-- Card Header --}}
            <div class="text-center mb-8">
                <h1 class="text-2xl font-extrabold text-slate-900">Create Account</h1>
                <p class="text-sm text-slate-500 mt-2">Join us to start your smart exam preparation.</p>
            </div>

            {{-- SEO & Title --}}
            <x-slot name="title">Register</x-slot>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block font-bold text-xs uppercase tracking-widest text-slate-500 mb-1">Full Name</label>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        class="mt-1 block w-full border-slate-200 focus:border-primary-500 focus:ring-primary-500 rounded-xl shadow-sm py-3 px-4 transition-all"
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="e.g. Rahim Ahmed"
                    >
                    @error('name')
                        <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block font-bold text-xs uppercase tracking-widest text-slate-500 mb-1">Phone Number</label>
                    <input 
                        id="phone" 
                        type="tel" 
                        name="phone" 
                        value="{{ old('phone') }}" 
                        class="mt-1 block w-full border-slate-200 focus:border-primary-500 focus:ring-primary-500 rounded-xl shadow-sm py-3 px-4 transition-all"
                        required 
                        autocomplete="tel"
                        placeholder="e.g. 01712345678"
                    >
                    @error('phone')
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
                        autocomplete="new-password"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block font-bold text-xs uppercase tracking-widest text-slate-500 mb-1">Confirm Password</label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        class="mt-1 block w-full border-slate-200 focus:border-primary-500 focus:ring-primary-500 rounded-xl shadow-sm py-3 px-4 transition-all"
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••"
                    >
                    @error('password_confirmation')
                        <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Register Button --}}
                <div class="pt-2">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-primary-800 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-primary-700 active:scale-[0.98] transition-all duration-150 shadow-lg shadow-primary-200">
                        Register
                    </button>
                </div>

                {{-- Login Link --}}
                <div class="text-center pt-4 border-t border-slate-50">
                    <p class="text-sm text-slate-500">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-bold text-primary-600 hover:text-primary-500 transition-colors underline decoration-primary-200 underline-offset-4">
                            Log in instead
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection