<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Department::class);

        $query = Department::with(['head', 'students']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $departments = $query->paginate($perPage);

        return DepartmentResource::collection($departments);
    }

    public function store(StoreDepartmentRequest $request)
    {
        $this->authorize('create', Department::class);

        $department = Department::create($request->validated());

        return new DepartmentResource($department->load('head'));
    }

    public function show(Department $department)
    {
        $this->authorize('view', $department);

        return new DepartmentResource($department->load(['head', 'students']));
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $this->authorize('update', $department);

        $department->update($request->validated());

        return new DepartmentResource($department->load('head'));
    }

    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

        $department->delete();

        return response()->json(['message' => 'Department deleted successfully'], 200);
    }
}

