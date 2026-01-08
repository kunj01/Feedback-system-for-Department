<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of academic years.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        // Mock data for now - will be connected to database
        $academicYears = [
            ['id' => 1, 'year' => '2024-25', 'startDate' => '2024-07-01', 'endDate' => '2025-06-30', 'status' => 'active'],
            ['id' => 2, 'year' => '2025-26', 'startDate' => '2025-07-01', 'endDate' => '2026-06-30', 'status' => 'inactive'],
        ];

        return view('admin.academic-years.index', compact('academicYears'));
    }

    /**
     * Show the form for creating a new academic year.
     */
    public function create()
    {
        $this->authorize('create', User::class);
        return view('admin.academic-years.create');
    }

    /**
     * Store a newly created academic year in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'year' => 'required|unique:academic_years|regex:/^\d{4}-\d{2}$/',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        // Store in database
        // AcademicYear::create($validated);

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic year created successfully.');
    }

    /**
     * Show the form for editing the specified academic year.
     */
    public function edit($id)
    {
        $this->authorize('update', User::class);
        
        // Fetch from database
        $academicYear = ['id' => $id, 'year' => '2025-26', 'startDate' => '2025-07-01', 'endDate' => '2026-06-30', 'status' => 'inactive'];

        return view('admin.academic-years.edit', compact('academicYear'));
    }

    /**
     * Update the specified academic year in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', User::class);

        $validated = $request->validate([
            'year' => 'required|unique:academic_years,year,' . $id . '|regex:/^\d{4}-\d{2}$/',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        // Update in database
        // AcademicYear::findOrFail($id)->update($validated);

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic year updated successfully.');
    }

    /**
     * Remove the specified academic year from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', User::class);

        // Delete from database
        // AcademicYear::findOrFail($id)->delete();

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic year deleted successfully.');
    }
}
