<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\StudentPlacement;
use App\Models\Student;
use App\Models\Company;
use App\Models\Project;
use App\Http\Requests\StorePlacementRequest;
use App\Http\Requests\UpdatePlacementRequest;
use Illuminate\Http\Request;

class PlacementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', StudentPlacement::class);

        $query = StudentPlacement::with(['student.user.department', 'company', 'project']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('job_title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('student.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by company
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Filter by placement type
        if ($request->filled('placement_type')) {
            $query->where('placement_type', $request->placement_type);
        }

        // Filter by confirmation status
        if ($request->filled('is_confirmed')) {
            $query->where('is_confirmed', $request->is_confirmed === 'yes');
        }

        $placements = $query->latest('offer_date')->paginate(15)->withQueryString();

        // Get filter options
        $students = Student::with('user')->get()->sortBy('user.name');
        $companies = Company::orderBy('name')->get();
        $placementTypes = ['Full-Time', 'Internship', 'Part-Time', 'Contract'];

        return view('placements.index', compact('placements', 'students', 'companies', 'placementTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', StudentPlacement::class);

        $students = Student::with('user')->get()->sortBy('user.name');
        $companies = Company::orderBy('name')->get();
        $projects = Project::with('student.user')->whereIn('status', ['In Progress', 'Completed'])->get();

        return view('placements.create', compact('students', 'companies', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlacementRequest $request)
    {
        $this->authorize('create', StudentPlacement::class);

        StudentPlacement::create($request->validated());

        return redirect()->route('placements.index')
            ->with('success', 'Placement record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentPlacement $placement)
    {
        $this->authorize('view', $placement);

        $placement->load(['student.user.department', 'company', 'project.guide']);

        return view('placements.show', compact('placement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentPlacement $placement)
    {
        $this->authorize('update', $placement);

        $students = Student::with('user')->get()->sortBy('user.name');
        $companies = Company::orderBy('name')->get();
        $projects = Project::with('student.user')->whereIn('status', ['In Progress', 'Completed'])->get();

        return view('placements.edit', compact('placement', 'students', 'companies', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlacementRequest $request, StudentPlacement $placement)
    {
        $this->authorize('update', $placement);

        $placement->update($request->validated());

        return redirect()->route('placements.index')
            ->with('success', 'Placement record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentPlacement $placement)
    {
        $this->authorize('delete', $placement);

        $placement->delete();

        return redirect()->route('placements.index')
            ->with('success', 'Placement record deleted successfully.');
    }

    /**
     * Confirm placement as final.
     */
    public function confirm(StudentPlacement $placement)
    {
        $this->authorize('update', $placement);

        // Check if student already has a confirmed placement
        $existingConfirmed = StudentPlacement::where('student_id', $placement->student_id)
            ->where('is_confirmed', true)
            ->where('id', '!=', $placement->id)
            ->exists();

        if ($existingConfirmed) {
            return redirect()->back()
                ->with('error', 'Student already has a confirmed placement. Please unconfirm the existing one first.');
        }

        $placement->update(['is_confirmed' => true]);

        return redirect()->back()
            ->with('success', 'Placement confirmed successfully.');
    }
}
