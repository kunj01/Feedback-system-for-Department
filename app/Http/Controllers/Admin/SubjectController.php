<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects
     */
    public function index(Request $request)
    {
        $semester = $request->get('semester');
        
        $query = Subject::with('teachers');
        
        if ($semester) {
            $query->bySemester($semester);
        }
        
        $subjects = $query->ordered()->get();
        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.subjects.index', compact('subjects', 'teachers', 'semester'));
    }

    /**
     * Store a newly created subject
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'semester' => 'required|integer|min:1|max:12',
            'description' => 'nullable|string',
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'exists:teachers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the maximum sort order for this semester
        $maxSortOrder = Subject::where('semester', $request->semester)->max('sort_order') ?? 0;

        $subject = Subject::create([
            'name' => $request->name,
            'code' => $request->code,
            'semester' => $request->semester,
            'description' => $request->description,
            'sort_order' => $maxSortOrder + 1,
            'is_active' => true,
        ]);

        // Attach teachers if provided
        if ($request->has('teacher_ids') && is_array($request->teacher_ids)) {
            $subject->teachers()->attach($request->teacher_ids);
        }

        $subject->load('teachers');

        return response()->json([
            'success' => true,
            'message' => 'Subject created successfully',
            'subject' => $subject
        ]);
    }

    /**
     * Update the specified subject
     */
    public function update(Request $request, Subject $subject)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $subject->id,
            'semester' => 'required|integer|min:1|max:12',
            'description' => 'nullable|string',
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'exists:teachers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $subject->update([
            'name' => $request->name,
            'code' => $request->code,
            'semester' => $request->semester,
            'description' => $request->description,
        ]);

        // Sync teachers
        if ($request->has('teacher_ids')) {
            $subject->teachers()->sync($request->teacher_ids ?? []);
        }

        $subject->load('teachers');

        return response()->json([
            'success' => true,
            'message' => 'Subject updated successfully',
            'subject' => $subject
        ]);
    }

    /**
     * Remove the specified subject
     */
    public function destroy(Subject $subject)
    {
        try {
            $subject->teachers()->detach();
            $subject->delete();

            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subject: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update sort order of subjects
     */
    public function updateSortOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'semester' => 'required|integer',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($request->subject_ids as $index => $subjectId) {
                Subject::where('id', $subjectId)
                    ->where('semester', $request->semester)
                    ->update(['sort_order' => $index + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sort order updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sort order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subjects by semester (AJAX)
     */
    public function getBySemester(Request $request)
    {
        $semester = $request->get('semester');
        
        $subjects = Subject::with('teachers')
            ->bySemester($semester)
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'subjects' => $subjects
        ]);
    }
}
