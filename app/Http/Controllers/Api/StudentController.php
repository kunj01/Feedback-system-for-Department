<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);

        $query = Student::with(['user', 'department', 'projects', 'placements']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('roll_no', 'like', "%{$search}%")
                  ->orWhere('registration_no', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%");
            });
        }

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('batch')) {
            $query->where('batch', $request->batch);
        }

        if ($request->has('training_status')) {
            $query->where('training_status', $request->training_status);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $students = $query->paginate($perPage);

        return StudentResource::collection($students);
    }

    public function store(StoreStudentRequest $request)
    {
        $this->authorize('create', Student::class);

        $student = Student::create($request->validated());

        return new StudentResource($student->load(['user', 'department']));
    }

    public function show(Student $student)
    {
        return new StudentResource($student->load([
            'user',
            'department',
            'projects',
            'placements',
            'evaluations',
            'reports'
        ]));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $this->authorize('update', $student);

        $student->update($request->validated());

        return new StudentResource($student->load(['user', 'department']));
    }

    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);

        $student->delete();

        return response()->json(['message' => 'Student deleted successfully'], 200);
    }
}

