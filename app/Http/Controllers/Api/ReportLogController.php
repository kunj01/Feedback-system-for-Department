<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReportLog;
use App\Http\Requests\StoreReportLogRequest;
use App\Http\Requests\UpdateReportLogRequest;
use App\Http\Resources\ReportLogResource;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class ReportLogController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', ReportLog::class);

        $query = ReportLog::with(['student.user', 'project', 'reviewer', 'creator']);

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('from_date')) {
            $query->where('submitted_date', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->where('submitted_date', '<=', $request->to_date);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort_by', 'submitted_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $reports = $query->paginate($request->get('per_page', 15));

        return ReportLogResource::collection($reports);
    }

    public function store(StoreReportLogRequest $request)
    {
        $this->authorize('create', ReportLog::class);

        $data = $request->validated();
        $data['created_by'] = auth()->id();

        if ($request->hasFile('file')) {
            $uploadResult = $this->fileUploadService->upload($request->file('file'), 'reports');
            $data['file_path'] = $uploadResult['path'];
        }

        $report = ReportLog::create($data);
        return new ReportLogResource($report->load(['student.user', 'project', 'reviewer', 'creator']));
    }

    public function show(ReportLog $reportLog)
    {
        $this->authorize('view', $reportLog);

        return new ReportLogResource($reportLog->load(['student.user', 'project', 'reviewer', 'creator']));
    }

    public function update(UpdateReportLogRequest $request, ReportLog $reportLog)
    {
        $this->authorize('update', $reportLog);

        $data = $request->validated();

        if ($request->hasFile('file')) {
            if ($reportLog->file_path) {
                $this->fileUploadService->delete($reportLog->file_path);
            }
            $uploadResult = $this->fileUploadService->upload($request->file('file'), 'reports');
            $data['file_path'] = $uploadResult['path'];
        }

        $reportLog->update($data);
        return new ReportLogResource($reportLog->load(['student.user', 'project', 'reviewer', 'creator']));
    }

    public function destroy(ReportLog $reportLog)
    {
        $this->authorize('delete', $reportLog);

        if ($reportLog->file_path) {
            $this->fileUploadService->delete($reportLog->file_path);
        }
        $reportLog->delete();
        return response()->json(['message' => 'Report deleted successfully']);
    }

    public function review(Request $request, ReportLog $reportLog)
    {
        $request->validate([
            'status' => ['required', 'in:REVIEWED,APPROVED,REJECTED'],
            'remarks' => ['nullable', 'string'],
        ]);

        $reportLog->update([
            'status' => $request->status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'remarks' => $request->remarks,
        ]);

        return new ReportLogResource($reportLog->load(['student.user', 'project', 'reviewer', 'creator']));
    }

    public function download(ReportLog $reportLog)
    {
        if (!$reportLog->file_path) {
            return response()->json(['message' => 'No file attached to this report'], 404);
        }

        if (!$this->fileUploadService->exists($reportLog->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return response()->download(
            storage_path('app/public/' . $reportLog->file_path),
            basename($reportLog->file_path)
        );
    }

    public function stats(Request $request)
    {
        $query = ReportLog::query();

        if ($request->has('from_date')) {
            $query->where('submitted_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->where('submitted_date', '<=', $request->to_date);
        }

        $stats = [
            'total_reports' => $query->count(),
            'by_status' => (clone $query)->selectRaw('status, count(*) as count')->groupBy('status')->get(),
            'by_type' => (clone $query)->selectRaw('report_type, count(*) as count')->groupBy('report_type')->get(),
            'pending_review' => (clone $query)->where('status', 'SUBMITTED')->count(),
            'approved' => (clone $query)->where('status', 'APPROVED')->count(),
            'rejected' => (clone $query)->where('status', 'REJECTED')->count(),
        ];

        return response()->json($stats);
    }
}
