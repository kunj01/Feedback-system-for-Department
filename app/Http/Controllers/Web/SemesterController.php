<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SemesterController extends Controller
{
    /**
     * Display a listing of semesters.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        // Mock data for now
        $semesters = [
            ['id' => 1, 'academic_year_id' => 1, 'semesterNumber' => 1, 'name' => 'Semester 1 (Odd)', 'startDate' => '2024-07-15', 'endDate' => '2024-11-30', 'status' => 'active'],
            ['id' => 2, 'academic_year_id' => 1, 'semesterNumber' => 2, 'name' => 'Semester 2 (Even)', 'startDate' => '2025-01-01', 'endDate' => '2025-05-30', 'status' => 'inactive'],
        ];

        $academicYears = [
            ['id' => 1, 'year' => '2024-25'],
            ['id' => 2, 'year' => '2025-26'],
        ];

        return view('admin.semesters.index', compact('semesters', 'academicYears'));
    }

    /**
     * Show the form for creating a new semester.
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        $academicYears = [
            ['id' => 1, 'year' => '2024-25'],
            ['id' => 2, 'year' => '2025-26'],
        ];

        return view('admin.semesters.create', compact('academicYears'));
    }

    /**
     * Store a newly created semester in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_number' => 'required|integer|between:1,8',
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,upcoming,closed',
        ]);

        // Store in database
        // Semester::create($validated);

        return redirect()->route('semesters.index')
            ->with('success', 'Semester created successfully.');
    }

    /**
     * Show the form for editing the specified semester.
     */
    public function edit($id)
    {
        $this->authorize('update', User::class);
        
        $semester = ['id' => $id, 'academic_year_id' => 1, 'semesterNumber' => 1, 'name' => 'Semester 1 (Odd)'];
        $academicYears = [['id' => 1, 'year' => '2024-25']];

        return view('admin.semesters.edit', compact('semester', 'academicYears'));
    }

    /**
     * Update the specified semester in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', User::class);

        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_number' => 'required|integer|between:1,8',
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,upcoming,closed',
        ]);

        // Update in database
        // Semester::findOrFail($id)->update($validated);

        return redirect()->route('semesters.index')
            ->with('success', 'Semester updated successfully.');
    }

    /**
     * Remove the specified semester from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', User::class);

        // Delete from database
        // Semester::findOrFail($id)->delete();

        return redirect()->route('semesters.index')
            ->with('success', 'Semester deleted successfully.');
    }
}
