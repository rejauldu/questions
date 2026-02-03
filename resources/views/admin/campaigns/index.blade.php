@extends('layout')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-secondary-800">Manage Hero Campaigns</h2>
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-bold animate-fade-in border border-green-200">
                {{ session('success') }}
            </div>
        @endif
    </div>

    {{-- Universal Form (Create & Edit) --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-secondary-200 mb-8 transition-all" id="form-container">
        <div class="flex items-center justify-between mb-4">
            <h3 id="form-title" class="text-sm font-bold text-primary-600 uppercase tracking-wider">Create New Campaign</h3>
            <button type="button" onclick="resetCampaignForm()" id="cancel-btn" class="hidden text-xs font-bold text-red-500 hover:underline uppercase">
                Cancel Editing
            </button>
        </div>

        <form id="campaign-form" action="{{ route('campaigns.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            
            <div>
                <label class="block text-[10px] font-bold text-secondary-500 uppercase mb-1">Target Institution</label>
                <select name="institution_id" id="inst_id" class="w-full border-secondary-200 rounded-lg text-sm focus:ring-primary-500">
                    @foreach($institutions as $inst)
                        <option value="{{ $inst->id }}">{{ institution($inst->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-secondary-500 uppercase mb-1">Tagline (e.g. এইচএসসি ২০২৬ এর জন্য)</label>
                <input type="text" name="tagline" id="tagline" placeholder="✨ Special Update" class="w-full border-secondary-200 rounded-lg text-sm" required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-secondary-500 uppercase mb-1">Headline (Use :name for Dynamic Institution Name)</label>
                <input type="text" name="headline" id="headline" placeholder="আপনার :name প্রস্তুতির জন্য নতুন প্রশ্ন পাওয়া গেছে!" class="w-full border-secondary-200 rounded-lg text-sm" required>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-secondary-500 uppercase mb-1">Target Post ID (Optional)</label>
                <input type="number" name="post_id" id="post_id" placeholder="e.g. 1024" class="w-full border-secondary-200 rounded-lg text-sm">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-secondary-500 uppercase mb-1">Button Text</label>
                <input type="text" name="button_text" id="btn_text" placeholder="পড়া শুরু করুন" class="w-full border-secondary-200 rounded-lg text-sm" required>
            </div>

            <div class="md:col-span-2">
                <button type="submit" id="submit-btn" class="w-full bg-primary-600 text-white font-bold py-2.5 rounded-lg hover:bg-primary-700 transition-all active:scale-95 shadow-md">
                    Save Campaign
                </button>
            </div>
        </form>
    </div>

    {{-- Campaigns List --}}
    <div class="bg-white rounded-xl shadow-sm border border-secondary-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-secondary-50 border-b border-secondary-200">
                    <tr>
                        <th class="px-4 py-3 font-bold text-secondary-600 uppercase text-[10px]">Institution</th>
                        <th class="px-4 py-3 font-bold text-secondary-600 uppercase text-[10px]">Headline Template</th>
                        <th class="px-4 py-3 font-bold text-secondary-600 uppercase text-[10px] text-center">Post ID</th>
                        <th class="px-4 py-3 font-bold text-secondary-600 uppercase text-[10px]">Status</th>
                        <th class="px-4 py-3 font-bold text-secondary-600 uppercase text-[10px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary-100">
                    @forelse($campaigns as $campaign)
                    <tr class="hover:bg-secondary-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="font-bold text-secondary-900">{{ institution($campaign->institution->name) }}</div>
                            <div class="text-[10px] text-secondary-400">{{ $campaign->tagline }}</div>
                        </td>
                        <td class="px-4 py-3 text-secondary-600 italic">
                            "{{ $campaign->headline }}"
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($campaign->post_id)
                                <span class="bg-secondary-100 text-secondary-700 px-2 py-0.5 rounded text-xs font-mono">#{{ $campaign->post_id }}</span>
                            @else
                                <span class="text-secondary-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('campaigns.toggle', $campaign->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $campaign->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $campaign->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-3">
                                <button onclick="editCampaign({{ $campaign->id }})" class="text-blue-600 hover:text-blue-800 font-bold text-[11px] uppercase tracking-tighter">
                                    Edit
                                </button>
                                <form action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-[11px] uppercase tracking-tighter">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-secondary-400 italic">No campaigns found. Create one above!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection