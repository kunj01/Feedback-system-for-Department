<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}
