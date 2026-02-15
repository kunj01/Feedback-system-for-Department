<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Batch;
use App\Models\Student;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display batch management page
     */
    public function index(Request $request)
    {
        $divisions = Division::with(['batches' => function ($query) {
            $query->withCount('students');
        }])
        ->active()
        ->orderBy('name')
        ->get();

        $selectedDivisionId = $request->get('division_id');
        $batches = [];
        $assignedStudents = [];
        $unassignedStudents = [];
        $selectedBatchId = null;

        if ($selectedDivisionId) {
            $batches = Batch::withCount('students')
                ->where('division_id', $selectedDivisionId)
                ->orderBy('batch_name')
                ->get();

            $selectedBatchId = $request->get('batch_id');
            
            if ($selectedBatchId) {
                // Get students already assigned to this batch
                $assignedStudents = Student::with(['user', 'division', 'batchGroup'])
                    ->where('batch_id', $selectedBatchId)
                    ->orderBy('enrollment_no')
                    ->get();
                
                // Get unassigned students from the same division
                $unassignedStudents = Student::with(['user', 'division'])
                    ->where('division_id', $selectedDivisionId)
                    ->whereNull('batch_id')
                    ->orderBy('enrollment_no')
                    ->get();
            }
        }

        return view('admin.batches.index', compact(
            'divisions',
            'selectedDivisionId',
            'batches',
            'assignedStudents',
            'unassignedStudents',
            'selectedBatchId'
        ));
    }

    /**
     * Store a new batch
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'batch_name' => 'required|string|max:10',
            'description' => 'nullable|string',
        ]);

        // Check if batch already exists
        $exists = Batch::where('division_id', $validated['division_id'])
            ->where('batch_name', $validated['batch_name'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Batch already exists for this division',
            ], 400);
        }

        $batch = Batch::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Batch created successfully',
            'data' => $batch,
        ]);
    }

    /**
     * Update batch
     */
    public function update(Request $request, Batch $batch)
    {
        $validated = $request->validate([
            'batch_name' => 'required|string|max:10',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $batch->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Batch updated successfully',
            'data' => $batch,
        ]);
    }

    /**
     * Delete batch
     */
    public function destroy(Batch $batch)
    {
        // Check if batch has students
        if ($batch->students()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete batch with students. Please reassign students first.',
            ], 400);
        }

        $batch->delete();

        return response()->json([
            'success' => true,
            'message' => 'Batch deleted successfully',
        ]);
    }

    /**
     * Get batches for a division (API endpoint)
     */
    public function getBatchesByDivision($divisionId)
    {
        $batches = Batch::withCount('students')
            ->where('division_id', $divisionId)
            ->where('is_active', true)
            ->orderBy('batch_name')
            ->get();

        return response()->json($batches);
    }

    /**
     * Get students in a batch (API endpoint)
     */
    public function getStudentsByBatch($batchId)
    {
        $students = Student::with(['user', 'division', 'batchGroup'])
            ->where('batch_id', $batchId)
            ->orderBy('enrollment_no')
            ->get();

        return response()->json($students);
    }

    /**
     * Get unassigned students for a division
     */
    public function getUnassignedStudents($divisionId)
    {
        $students = Student::with(['user', 'division'])
            ->where('division_id', $divisionId)
            ->whereNull('batch_id')
            ->orderBy('enrollment_no')
            ->get();

        return response()->json($students);
    }

    /**
     * Assign students to a batch
     */
    public function assignStudents(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $batch = Batch::findOrFail($validated['batch_id']);
        
        // Update students to assign them to the batch
        $updated = Student::whereIn('id', $validated['student_ids'])
            ->update([
                'batch_id' => $batch->id,
                'division_id' => $batch->division_id, // Ensure division is also set
            ]);

        return response()->json([
            'success' => true,
            'message' => "Successfully assigned {$updated} student(s) to batch {$batch->batch_name}",
            'count' => $updated,
        ]);
    }

    /**
     * Remove students from a batch
     */
    public function unassignStudents(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $updated = Student::whereIn('id', $validated['student_ids'])
            ->update(['batch_id' => null]);

        return response()->json([
            'success' => true,
            'message' => "Successfully unassigned {$updated} student(s) from batch",
            'count' => $updated,
        ]);
    }
}
