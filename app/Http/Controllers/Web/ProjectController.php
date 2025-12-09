<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Project::class);

        $query = Project::with(['student.user', 'guide']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('student.user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Academic year filter
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Guide filter
        if ($request->filled('guide')) {
            $query->where('guide_id', $request->guide);
        }

        $projects = $query->latest()->paginate(15);

        // Get unique academic years for filter
        $academicYears = Project::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        $guides = User::role('guide')->get();

        return view('projects.index', compact('projects', 'academicYears', 'guides'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        $this->authorize('create', Project::class);

        $students = Student::with('user')->get();
        $guides = User::role('guide')->get();

        return view('projects.create', compact('students', 'guides'));
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'guide_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'academic_year' => ['required', 'string', 'max:20'],
            'status' => ['required', 'in:Proposed,In Progress,Completed,On Hold'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
        ]);

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $project->load(['student.user.department', 'guide', 'evaluations.evaluatedBy']);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $students = Student::with('user')->get();
        $guides = User::role('guide')->get();

        return view('projects.edit', compact('project', 'students', 'guides'));
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'guide_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'academic_year' => ['required', 'string', 'max:20'],
            'status' => ['required', 'in:Proposed,In Progress,Completed,On Hold'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
