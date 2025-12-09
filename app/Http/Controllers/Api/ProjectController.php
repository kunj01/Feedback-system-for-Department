<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->middleware('auth:sanctum');
        $this->projectService = $projectService;
    }

    public function index(Request $request)
    {
        $query = Project::with(['company', 'guide', 'creator', 'students']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_id', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('guide_id')) {
            $query->where('guide_id', $request->guide_id);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $projects = $query->paginate($perPage);

        return ProjectResource::collection($projects);
    }

    public function store(StoreProjectRequest $request)
    {
        $this->authorize('create', Project::class);

        $data = $request->validated();

        // Auto-generate project_id
        $data['project_id'] = $this->projectService->generateProjectId();
        $data['created_by'] = auth()->id();

        $project = Project::create($data);

        return new ProjectResource($project->load(['company', 'guide', 'creator']));
    }

    public function show(Project $project)
    {
        return new ProjectResource($project->load([
            'company',
            'guide',
            'creator',
            'students',
            'evaluations',
            'reports'
        ]));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $project->update($request->validated());

        return new ProjectResource($project->load(['company', 'guide', 'creator']));
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully'], 200);
    }

    /**
     * Assign students to project
     */
    public function assignStudents(Request $request, Project $project)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $syncData = [];
        foreach ($request->student_ids as $studentId) {
            $syncData[$studentId] = [
                'assigned_on' => now(),
                'role_in_project' => $request->input("role_{$studentId}", null),
            ];
        }

        $project->students()->sync($syncData);

        return new ProjectResource($project->load('students'));
    }

    /**
     * Remove student from project
     */
    public function removeStudent(Project $project, $studentId)
    {
        $project->students()->detach($studentId);

        return response()->json(['message' => 'Student removed from project'], 200);
    }

    /**
     * Update project status
     */
    public function updateStatus(Request $request, Project $project)
    {
        $request->validate([
            'status' => 'required|in:OPEN,IN_PROGRESS,COMPLETED,CANCELLED',
        ]);

        $project->update(['status' => $request->status]);

        return new ProjectResource($project);
    }
}

