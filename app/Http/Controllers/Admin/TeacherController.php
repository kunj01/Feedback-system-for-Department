<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers
     */
    public function index()
    {
        $teachers = Teacher::with('subjects')->orderBy('name')->get();
        
        return view('admin.teachers.index', compact('teachers'));
    }

    /**
     * Store a newly created teacher
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $teacher = Teacher::create([
            'name' => $request->name,
            'email' => $request->email,
            'department' => $request->department,
            'designation' => $request->designation,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Teacher created successfully',
            'teacher' => $teacher
        ]);
    }

    /**
     * Update the specified teacher
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $teacher->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Teacher updated successfully',
            'teacher' => $teacher
        ]);
    }

    /**
     * Remove the specified teacher
     */
    public function destroy(Teacher $teacher)
    {
        try {
            $teacher->subjects()->detach();
            $teacher->delete();

            return response()->json([
                'success' => true,
                'message' => 'Teacher deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all active teachers (AJAX)
     */
    public function getActive()
    {
        $teachers = Teacher::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'department', 'designation']);

        return response()->json([
            'success' => true,
            'teachers' => $teachers
        ]);
    }

    /**
     * Show batch assignment view for a teacher
     */
    public function showBatchAssignments(Teacher $teacher)
    {
        $teacher->load(['batches.division', 'subjects']);
        
        // Get all divisions with their batches
        $divisions = \App\Models\Division::with('batches')
            ->orderBy('semester')
            ->orderBy('name')
            ->get();
        
        // Get all subjects
        $subjects = \App\Models\Subject::where('is_active', true)
            ->orderBy('semester')
            ->orderBy('sort_order')
            ->get();
        
        return view('admin.teachers.batch-assignments', compact('teacher', 'divisions', 'subjects'));
    }

    /**
     * Assign batches to a teacher
     */
    public function assignBatches(Request $request, Teacher $teacher)
    {
        $validator = Validator::make($request->all(), [
            'batch_ids' => 'required|array',
            'batch_ids.*' => 'exists:batches,id',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
            'types' => 'required|array',
            'types.*' => 'in:theory,lab',
            'notes' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $assignedCount = 0;
        
        foreach ($request->batch_ids as $index => $batchId) {
            $type = $request->types[$index] ?? 'theory';
            $subjectId = isset($request->subject_ids[$index]) ? $request->subject_ids[$index] : null;
            $notes = isset($request->notes[$index]) ? $request->notes[$index] : null;
            
            // Check if this combination already exists
            $exists = $teacher->batches()
                ->wherePivot('batch_id', $batchId)
                ->wherePivot('subject_id', $subjectId)
                ->wherePivot('type', $type)
                ->exists();
            
            if (!$exists) {
                $teacher->batches()->attach($batchId, [
                    'subject_id' => $subjectId,
                    'type' => $type,
                    'notes' => $notes,
                ]);
                $assignedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully assigned {$assignedCount} batch(es) to {$teacher->name}",
        ]);
    }

    /**
     * Remove batch assignment from teacher
     */
    public function unassignBatch(Request $request, Teacher $teacher)
    {
        $validator = Validator::make($request->all(), [
            'pivot_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Delete the specific pivot record
        \DB::table('batch_teacher')
            ->where('id', $request->pivot_id)
            ->where('teacher_id', $teacher->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Batch assignment removed successfully',
        ]);
    }
}
