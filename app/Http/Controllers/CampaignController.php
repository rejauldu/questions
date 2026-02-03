<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Institution;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with('institution')->get();
        $institutions = Institution::orderBy('name')->get();
        return view('admin.campaigns.index', compact('campaigns', 'institutions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'institution_id' => 'required|exists:institutions,id|unique:campaigns,institution_id',
            'tagline'        => 'required|string|max:100',
            'headline'       => 'required|string|max:255',
            'button_text'    => 'required|string|max:50',
            'post_id'        => 'nullable|integer|exists:posts,id', // Added post_id validation
        ]);

        Campaign::create($data);
        return back()->with('success', 'Campaign created successfully!');
    }

    public function toggle($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->is_active = !$campaign->is_active;
        $campaign->save();
        return back();
    }

    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        return response()->json($campaign);
    }

    public function update(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);
        
        $data = $request->validate([
            'institution_id' => 'required|exists:institutions,id|unique:campaigns,institution_id,' . $id,
            'tagline'        => 'required|string|max:100',
            'headline'       => 'required|string|max:255',
            'button_text'    => 'required|string|max:50',
            'post_id'        => 'nullable|integer|exists:posts,id', // Added post_id validation
        ]);

        $campaign->update($data);
        return back()->with('success', 'Campaign updated successfully!');
    }

    public function destroy($id)
    {
        Campaign::destroy($id);
        return back()->with('success', 'Campaign deleted.');
    }
}