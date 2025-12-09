<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Project;
use App\Models\Student;
use App\Models\User;
use App\Http\Requests\StoreEvaluationRequest;
use App\Http\Requests\UpdateEvaluationRequest;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Evaluation::class);

        $query = Evaluation::with(['project.student.user', 'student.user', 'guide']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('remarks', 'like', "%{$search}%")
                    ->orWhere('internal_exam_grade', 'like', "%{$search}%")
                    ->orWhereHas('student.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('project', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by guide
        if ($request->filled('guide_id')) {
            $query->where('guide_id', $request->guide_id);
        }

        // Filter by mode
        if ($request->filled('mode')) {
            $query->where('mode', $request->mode);
        }

        // Filter by grade
        if ($request->filled('grade')) {
            $query->where('internal_exam_grade', $request->grade);
        }

        $evaluations = $query->latest('evaluation_date')->paginate(15)->withQueryString();

        // Get filter options
        $projects = Project::with('student.user')->orderBy('title')->get();
        $students = Student::with('user')->get()->sortBy('user.name');
        $guides = User::role(['Guide', 'Admin', 'TnP'])->orderBy('name')->get();
        $grades = ['A+', 'A', 'B+', 'B', 'C', 'F'];

        return view('evaluations.index', compact('evaluations', 'projects', 'students', 'guides', 'grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Evaluation::class);

        $projects = Project::with('student.user')->whereIn('status', ['In Progress', 'Completed'])->get();
        $students = Student::with('user')->get()->sortBy('user.name');
        $guides = User::role(['Guide', 'Admin', 'TnP', 'Head'])->orderBy('name')->get();

        return view('evaluations.create', compact('projects', 'students', 'guides'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEvaluationRequest $request)
    {
        $this->authorize('create', Evaluation::class);

        $data = $request->validated();

        // Auto-calculate grade based on internal exam marks
        if (isset($data['internal_exam_marks'])) {
            $data['internal_exam_grade'] = $this->projectService->calculateGrade($data['internal_exam_marks']);
        }

        Evaluation::create($data);

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        $this->authorize('view', $evaluation);

        $evaluation->load(['project.student.user.department', 'student.user.department', 'guide']);

        return view('evaluations.show', compact('evaluation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        $this->authorize('update', $evaluation);

        $projects = Project::with('student.user')->whereIn('status', ['In Progress', 'Completed'])->get();
        $students = Student::with('user')->get()->sortBy('user.name');
        $guides = User::role(['Guide', 'Admin', 'TnP', 'Head'])->orderBy('name')->get();

        return view('evaluations.edit', compact('evaluation', 'projects', 'students', 'guides'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        $this->authorize('update', $evaluation);

        $data = $request->validated();

        // Recalculate grade if internal exam marks are updated
        if (isset($data['internal_exam_marks'])) {
            $data['internal_exam_grade'] = $this->projectService->calculateGrade($data['internal_exam_marks']);
        }

        $evaluation->update($data);

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        $this->authorize('delete', $evaluation);

        $evaluation->delete();

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluation deleted successfully.');
    }
}
