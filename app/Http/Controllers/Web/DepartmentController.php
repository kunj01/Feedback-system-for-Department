<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Department::class);

        $query = Department::with('head');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $departments = $query->latest()->paginate(15);

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department
     */
    public function create()
    {
        $this->authorize('create', Department::class);

        $heads = User::role('head')->get();

        return view('departments.create', compact('heads'));
    }

    /**
     * Store a newly created department
     */
    public function store(Request $request)
    {
        $this->authorize('create', Department::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:departments,code'],
            'head_id' => ['nullable', 'exists:users,id'],
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified department
     */
    public function show(Department $department)
    {
        $this->authorize('view', $department);

        $department->load(['head', 'students.user']);

        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified department
     */
    public function edit(Department $department)
    {
        $this->authorize('update', $department);

        $heads = User::role('head')->get();

        return view('departments.edit', compact('department', 'heads'));
    }

    /**
     * Update the specified department
     */
    public function update(Request $request, Department $department)
    {
        $this->authorize('update', $department);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('departments')->ignore($department->id)],
            'head_id' => ['nullable', 'exists:users,id'],
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department
     */
    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

        // Check if department has students
        if ($department->students()->count() > 0) {
            return back()->with('error', 'Cannot delete department with assigned students.');
        }

        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
