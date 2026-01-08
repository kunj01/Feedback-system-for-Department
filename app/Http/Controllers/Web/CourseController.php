<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        // Mock data for now
        $courses = [
            ['id' => 1, 'code' => 'CS101', 'name' => 'Data Structures', 'department' => 'CSE', 'semester' => 1, 'credits' => 4, 'type' => 'Theory', 'faculty' => 'Dr. Singh'],
            ['id' => 2, 'code' => 'CS102', 'name' => 'Web Development', 'department' => 'CSE', 'semester' => 1, 'credits' => 3, 'type' => 'Practical', 'faculty' => 'Prof. Sharma'],
        ];

        $departments = ['CSE', 'IT', 'ECE', 'ME'];
        $semesters = [1, 2, 3, 4, 5, 6, 7, 8];

        return view('admin.courses.index', compact('courses', 'departments', 'semesters'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        $departments = ['CSE', 'IT', 'ECE', 'ME'];
        $semesters = [1, 2, 3, 4, 5, 6, 7, 8];
        $faculty = [
            ['id' => 1, 'name' => 'Dr. Singh'],
            ['id' => 2, 'name' => 'Prof. Sharma'],
            ['id' => 3, 'name' => 'Dr. Patel'],
        ];

        return view('admin.courses.create', compact('departments', 'semesters', 'faculty'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'code' => 'required|unique:courses|string|max:20',
            'name' => 'required|string|max:150',
            'department_id' => 'required|string',
            'semester' => 'required|integer|between:1,8',
            'credits' => 'required|numeric|min:1|max:6',
            'type' => 'required|in:Theory,Practical,Elective',
            'description' => 'nullable|string',
            'faculty_ids' => 'required|array|min:1',
        ]);

        // Store in database
        // Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit($id)
    {
        $this->authorize('update', User::class);
        
        $course = ['id' => $id, 'code' => 'CS101', 'name' => 'Data Structures'];
        $departments = ['CSE', 'IT', 'ECE', 'ME'];
        $semesters = [1, 2, 3, 4, 5, 6, 7, 8];
        $faculty = [['id' => 1, 'name' => 'Dr. Singh']];

        return view('admin.courses.edit', compact('course', 'departments', 'semesters', 'faculty'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', User::class);

        $validated = $request->validate([
            'code' => 'required|unique:courses,code,' . $id . '|string|max:20',
            'name' => 'required|string|max:150',
            'department_id' => 'required|string',
            'semester' => 'required|integer|between:1,8',
            'credits' => 'required|numeric|min:1|max:6',
            'type' => 'required|in:Theory,Practical,Elective',
            'description' => 'nullable|string',
            'faculty_ids' => 'required|array|min:1',
        ]);

        // Update in database
        // Course::findOrFail($id)->update($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', User::class);

        // Delete from database
        // Course::findOrFail($id)->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
