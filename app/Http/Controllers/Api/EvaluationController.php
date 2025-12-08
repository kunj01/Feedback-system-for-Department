<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Http\Requests\StoreEvaluationRequest;
use App\Http\Requests\UpdateEvaluationRequest;
use App\Http\Resources\EvaluationResource;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Evaluation::class);

        $query = Evaluation::with(['project', 'student', 'evaluator']);

        // Filter by project
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by student
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by evaluator
        if ($request->has('evaluator_id')) {
            $query->where('evaluator_id', $request->evaluator_id);
        }

        // Filter by evaluation type
        if ($request->has('evaluation_type')) {
            $query->where('evaluation_type', $request->evaluation_type);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->where('evaluation_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->where('evaluation_date', '<=', $request->to_date);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('feedback', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%")
                    ->orWhere('grade', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'evaluation_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $evaluations = $query->paginate($request->get('per_page', 15));

        return EvaluationResource::collection($evaluations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEvaluationRequest $request)
    {
        $this->authorize('create', Evaluation::class);

        $data = $request->validated();

        // Auto-calculate grade if marks are provided
        if (isset($data['marks_obtained']) && isset($data['total_marks'])) {
            if (empty($data['grade'])) {
                $data['grade'] = $this->projectService->calculateGrade($data['marks_obtained']);
            }
        }

        $evaluation = Evaluation::create($data);

        return new EvaluationResource($evaluation->load(['project', 'student', 'evaluator']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        $this->authorize('view', $evaluation);

        return new EvaluationResource($evaluation->load(['project', 'student', 'evaluator']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        $this->authorize('update', $evaluation);

        $data = $request->validated();

        // Recalculate grade if marks are updated
        if (isset($data['marks_obtained'])) {
            $data['grade'] = $this->projectService->calculateGrade($data['marks_obtained']);
        }

        $evaluation->update($data);

        return new EvaluationResource($evaluation->load(['project', 'student', 'evaluator']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        $this->authorize('delete', $evaluation);

        $evaluation->delete();

        return response()->json([
            'message' => 'Evaluation deleted successfully'
        ]);
    }

    /**
     * Get evaluation statistics for a project
     */
    public function projectStats($projectId)
    {
        $this->authorize('viewAny', Evaluation::class);

        $stats = [
            'total_evaluations' => Evaluation::where('project_id', $projectId)->count(),
            'average_marks' => Evaluation::where('project_id', $projectId)->avg('marks_obtained'),
            'by_type' => Evaluation::where('project_id', $projectId)
                ->selectRaw('evaluation_type, count(*) as count, avg(marks_obtained) as avg_marks')
                ->groupBy('evaluation_type')
                ->get(),
            'grade_distribution' => Evaluation::where('project_id', $projectId)
                ->selectRaw('grade, count(*) as count')
                ->groupBy('grade')
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Get evaluation statistics for a student
     */
    public function studentStats($studentId)
    {
        $this->authorize('viewAny', Evaluation::class);

        $stats = [
            'total_evaluations' => Evaluation::where('student_id', $studentId)->count(),
            'average_marks' => Evaluation::where('student_id', $studentId)->avg('marks_obtained'),
            'by_type' => Evaluation::where('student_id', $studentId)
                ->selectRaw('evaluation_type, count(*) as count, avg(marks_obtained) as avg_marks')
                ->groupBy('evaluation_type')
                ->get(),
            'recent_evaluations' => EvaluationResource::collection(
                Evaluation::where('student_id', $studentId)
                    ->with(['project', 'evaluator'])
                    ->latest('evaluation_date')
                    ->limit(5)
                    ->get()
            ),
        ];

        return response()->json($stats);
    }
}
