<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);

        $query = Student::with(['user', 'department']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('enrollment_number', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Department filter
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        // Academic year filter
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Placement status filter
        if ($request->filled('placement_status')) {
            $query->where('placement_status', $request->placement_status);
        }

        $students = $query->latest()->paginate(15);
        $departments = Department::all();

        // Get unique academic years for filter
        $academicYears = Student::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        return view('students.index', compact('students', 'departments', 'academicYears'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        $this->authorize('create', Student::class);

        $departments = Department::all();
        $users = User::role('student')->whereDoesntHave('student')->get();

        return view('students.create', compact('departments', 'users'));
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $this->authorize('create', Student::class);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id', 'unique:students,user_id'],
            'department_id' => ['required', 'exists:departments,id'],
            'enrollment_number' => ['required', 'string', 'unique:students,enrollment_number'],
            'contact_number' => ['required', 'string', 'max:15'],
            'academic_year' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'integer', 'min:1', 'max:8'],
            'cgpa' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'placement_status' => ['required', 'in:Not Placed,Placed,Pursuing Higher Studies'],
        ]);

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        $this->authorize('view', $student);

        $student->load(['user', 'department', 'projects.guide', 'evaluations', 'placements.company']);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student)
    {
        $this->authorize('update', $student);

        $departments = Department::all();

        return view('students.edit', compact('student', 'departments'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student)
    {
        $this->authorize('update', $student);

        $validated = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'enrollment_number' => ['required', 'string', Rule::unique('students')->ignore($student->id)],
            'contact_number' => ['required', 'string', 'max:15'],
            'academic_year' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'integer', 'min:1', 'max:8'],
            'cgpa' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'placement_status' => ['required', 'in:Not Placed,Placed,Pursuing Higher Studies'],
        ]);

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
