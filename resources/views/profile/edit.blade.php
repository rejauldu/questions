@extends('layout')

@section('content')
<div class="bg-slate-50 min-h-screen py-8 px-4">
    <div class="max-w-xl mx-auto">
        
        {{-- Header --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('profile.show') }}" class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center border border-slate-200 text-slate-400 hover:text-primary-600 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-2xl font-black text-slate-900 leading-none">Edit Profile</h1>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-5">
                
                {{-- Name Field --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 px-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                        class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-primary-500 transition-all"
                        placeholder="Enter your name">
                    @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                {{-- Institution/Exam Selection --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 px-1">Target Exam (Institution)</label>
                    <select name="institution_id" 
                        class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-slate-700 font-bold focus:ring-2 focus:ring-primary-500 transition-all appearance-none">
                        @foreach($institutions as $inst)
                            <option value="{{ $inst->id }}" {{ $user->institution_id == $inst->id ? 'selected' : '' }}>
                                {{ institution($inst->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- HSC Group (Conditional or Optional) --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 px-1">Group (Optional)</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['Science', 'Arts', 'Commerce'] as $group)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="hsc_group" value="{{ $group }}" class="peer sr-only" {{ $user->hsc_group == $group ? 'checked' : '' }}>
                                <div class="bg-slate-50 text-slate-500 text-xs font-bold py-3 text-center rounded-xl border border-transparent peer-checked:bg-primary-50 peer-checked:text-primary-600 peer-checked:border-primary-200 transition-all">
                                    {{ $group }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-primary-200 transition-all active:scale-[0.98]">
                Save Changes
            </button>

        </form>

    </div>
</div>
@endsection