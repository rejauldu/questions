<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class SubjectDateController extends Controller
{
    /**
     * Show the main subject date editing page with initial data.
     */
    public function index()
    {
        // 1. Get all institutions for the primary dropdown
        $institutions = Institution::all(['id', 'name']);

        // 2. Get all distinct years available in the subjects table
        // We use a query builder to ensure we only get years where the subject exists
        $years = Subject::distinct()->orderBy('year')->pluck('year')->toArray();

        // If no years exist, default to the current year
        if (empty($years)) {
            $years = [date('Y')];
        }
        return Inertia::render('Routines/Edit', [
            'institutions' => $institutions,
            'years' => $years,
        ]);
    }

    /**
     * API endpoint to get available classes for a selected institution and year.
     */
    public function getClasses(Request $request)
    {
        $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'year' => 'required|integer',
        ]);

        $classes = Subject::where('institution_id', $request->institution_id)
            ->whereYear('exam_at', $request->year)
            ->selectRaw('DISTINCT class')
            ->whereNotNull('class')
            ->orderBy('class')
            ->pluck('class');

        return response()->json([
            'classes' => $classes,
            // Flag to indicate if the class filter should be shown
            'shouldShowClassFilter' => $classes->isNotEmpty(),
        ]);
    }

    /**
     * API endpoint to fetch subjects based on filters.
     */
    public function getSubjects(Request $request)
    {
        $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'year' => 'required|integer',
            'class' => 'nullable|string',
        ]);

        $query = Subject::where('institution_id', $request->institution_id);

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        } else {
            $query->where(function ($q) {
                $q->whereNull('class')->orWhere('class', '');
            });
        }

        $subjects = $query->orderBy('name')->get([
            'id', 'name', 'class', 'year', 'exam_at', 'description'
        ])->map(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'year' => $subject->year,
                'class' => $subject->class,
                'exam_at' => $subject->exam_at ? $subject->exam_at->format('Y-m-d\TH:i') : null,
                'description' => $subject->description,
                'is_null_date' => $subject->exam_at === null,
            ];
        });

        return response()->json(['subjects' => $subjects]);
    }

    /**
     * API endpoint to handle the bulk update of subject exam dates and descriptions.
     */
    public function updateDates(Request $request)
    {
        // Define the validation rules for the incoming array of subject updates
        $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|exists:subjects,id',
            'updates.*.exam_at' => 'nullable|date',
            'updates.*.description' => 'nullable|string|max:500',
            'updates.*.is_null_date' => 'required|boolean',
            'updates.*.status' => 'required|boolean',
        ]);

        $updatesCount = 0;

        foreach ($request->updates as $data) {
            $subject = Subject::find($data['id']);

            if ($subject) {
                // If the user checked the 'null date' box, set exam_at to null.
                // Otherwise, use the provided date string.
                $examAt = $data['is_null_date'] ? null : $data['exam_at'];

                $subject->exam_at = $examAt;
                $subject->description = $data['description'];
                $subject->status = $data['status'];
                $subject->save();
                $updatesCount++;
            }
        }

        return redirect()->back()->with('success', "Successfully updated {$updatesCount} subject dates.");
    }
}