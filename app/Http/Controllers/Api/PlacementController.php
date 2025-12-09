<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentPlacement;
use App\Http\Requests\StorePlacementRequest;
use App\Http\Requests\UpdatePlacementRequest;
use App\Http\Resources\PlacementResource;
use Illuminate\Http\Request;

class PlacementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', StudentPlacement::class);

        $query = StudentPlacement::with(['student.user', 'company']);

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('placement_type')) {
            $query->where('placement_type', $request->placement_type);
        }

        if ($request->has('is_confirmed')) {
            $query->where('is_confirmed', $request->is_confirmed === 'true' || $request->is_confirmed === '1');
        }

        if ($request->has('min_package')) {
            $query->where('package_lpa', '>=', $request->min_package);
        }
        if ($request->has('max_package')) {
            $query->where('package_lpa', '<=', $request->max_package);
        }

        if ($request->has('from_date')) {
            $query->where('offer_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->where('offer_date', '<=', $request->to_date);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('job_title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort_by', 'offer_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $placements = $query->paginate($request->get('per_page', 15));

        return PlacementResource::collection($placements);
    }

    public function store(StorePlacementRequest $request)
    {
        $this->authorize('create', StudentPlacement::class);

        $data = $request->validated();
        $placement = StudentPlacement::create($data);
        return new PlacementResource($placement->load(['student.user', 'company']));
    }

    public function show(StudentPlacement $placement)
    {
        $this->authorize('view', $placement);

        return new PlacementResource($placement->load(['student.user', 'company']));
    }

    public function update(UpdatePlacementRequest $request, StudentPlacement $placement)
    {
        $this->authorize('update', $placement);

        $placement->update($request->validated());
        return new PlacementResource($placement->load(['student.user', 'company']));
    }

    public function destroy(StudentPlacement $placement)
    {
        $this->authorize('delete', $placement);

        $placement->delete();
        return response()->json(['message' => 'Placement deleted successfully']);
    }

    public function confirmPlacement(StudentPlacement $placement)
    {
        $placement->update([
            'is_confirmed' => true,
            'confirmed_date' => now()
        ]);
        return new PlacementResource($placement->load(['student.user', 'company']));
    }

    public function stats(Request $request)
    {
        $query = StudentPlacement::query();

        if ($request->has('from_date')) {
            $query->where('offer_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->where('offer_date', '<=', $request->to_date);
        }

        $stats = [
            'total_placements' => $query->count(),
            'confirmed_placements' => (clone $query)->where('is_confirmed', true)->count(),
            'pending_placements' => (clone $query)->where('is_confirmed', false)->count(),
            'average_package' => $query->avg('package_lpa'),
            'highest_package' => $query->max('package_lpa'),
            'lowest_package' => $query->min('package_lpa'),
            'by_type' => StudentPlacement::selectRaw('placement_type, count(*) as count, avg(package_lpa) as avg_package')
                ->groupBy('placement_type')
                ->get(),
            'top_companies' => StudentPlacement::with('company')
                ->selectRaw('company_id, count(*) as placement_count')
                ->groupBy('company_id')
                ->orderByDesc('placement_count')
                ->limit(10)
                ->get(),
        ];

        return response()->json($stats);
    }

    public function studentPlacements($studentId)
    {
        $placements = StudentPlacement::where('student_id', $studentId)
            ->with(['company'])
            ->orderBy('offer_date', 'desc')
            ->get();
        return PlacementResource::collection($placements);
    }

    public function companyPlacements($companyId)
    {
        $placements = StudentPlacement::where('company_id', $companyId)
            ->with(['student.user'])
            ->orderBy('offer_date', 'desc')
            ->paginate(15);
        return PlacementResource::collection($placements);
    }
}
