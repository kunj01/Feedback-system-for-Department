<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Student;
use App\Models\StudentPlacement;
use App\Models\Project;
use App\Models\Evaluation;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);

        // Get filter parameters
        $departmentId = $request->input('department_id');
        $academicYear = $request->input('academic_year');
        $placementType = $request->input('placement_type');

        // Academic years for filter (last 5 years)
        $currentYear = date('Y');
        $academicYears = [];
        for ($i = 0; $i < 5; $i++) {
            $year = $currentYear - $i;
            $academicYears[] = $year . '-' . ($year + 1);
        }

        // Get departments for filter
        $departments = Department::all();

        // Build query for placement statistics
        $placementsQuery = StudentPlacement::with(['student.user.department', 'company'])
            ->when($departmentId, function ($query, $departmentId) {
                $query->whereHas('student.user', function ($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            })
            ->when($academicYear, function ($query, $academicYear) {
                [$startYear, $endYear] = explode('-', $academicYear);
                $query->whereBetween('offer_date', [
                    $startYear . '-06-01',
                    $endYear . '-05-31'
                ]);
            })
            ->when($placementType, function ($query, $placementType) {
                $query->where('placement_type', $placementType);
            });

        // Placement statistics
        $totalPlacements = (clone $placementsQuery)->count();
        $confirmedPlacements = (clone $placementsQuery)->where('is_confirmed', true)->count();
        $averagePackage = (clone $placementsQuery)->where('is_confirmed', true)->avg('package_lpa') ?? 0;
        $highestPackage = (clone $placementsQuery)->where('is_confirmed', true)->max('package_lpa') ?? 0;

        // Placements by type
        $placementsByType = (clone $placementsQuery)
            ->where('is_confirmed', true)
            ->select('placement_type', DB::raw('count(*) as count'))
            ->groupBy('placement_type')
            ->get();

        // Top hiring companies
        $topCompanies = (clone $placementsQuery)
            ->where('is_confirmed', true)
            ->select('company_id', DB::raw('count(*) as placements_count'))
            ->groupBy('company_id')
            ->orderByDesc('placements_count')
            ->limit(10)
            ->with('company')
            ->get();

        // Department-wise placement statistics
        $departmentStats = StudentPlacement::where('is_confirmed', true)
            ->when($academicYear, function ($query, $academicYear) {
                [$startYear, $endYear] = explode('-', $academicYear);
                $query->whereBetween('offer_date', [
                    $startYear . '-06-01',
                    $endYear . '-05-31'
                ]);
            })
            ->whereHas('student.user.department')
            ->with('student.user.department')
            ->get()
            ->groupBy('student.user.department_id')
            ->map(function ($placements, $deptId) {
                $dept = Department::find($deptId);
                return [
                    'department' => $dept->name ?? 'Unknown',
                    'total_placements' => $placements->count(),
                    'average_package' => round($placements->avg('package_lpa'), 2),
                    'highest_package' => $placements->max('package_lpa')
                ];
            })
            ->values();

        // Project completion statistics
        $projectStats = [
            'total' => Project::count(),
            'completed' => Project::where('status', 'COMPLETED')->count(),
            'in_progress' => Project::where('status', 'IN_PROGRESS')->count(),
            'planning' => Project::where('status', 'PLANNING')->count(),
            'on_hold' => Project::where('status', 'ON_HOLD')->count(),
        ];

        // Recent placements
        $recentPlacements = (clone $placementsQuery)
            ->where('is_confirmed', true)
            ->orderByDesc('offer_date')
            ->limit(10)
            ->get();

        return view('reports.index', compact(
            'totalPlacements',
            'confirmedPlacements',
            'averagePackage',
            'highestPackage',
            'placementsByType',
            'topCompanies',
            'departmentStats',
            'projectStats',
            'recentPlacements',
            'departments',
            'academicYears',
            'departmentId',
            'academicYear',
            'placementType'
        ));
    }

    /**
     * Display placement report
     */
    public function placements(Request $request)
    {
        $this->authorize('viewAny', StudentPlacement::class);

        // Get filters
        $departmentId = $request->input('department_id');
        $companyId = $request->input('company_id');
        $placementType = $request->input('placement_type');
        $isConfirmed = $request->input('is_confirmed');

        $placements = StudentPlacement::with(['student.user.department', 'company', 'project'])
            ->when($departmentId, function ($query, $departmentId) {
                $query->whereHas('student.user', function ($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            })
            ->when($companyId, function ($query, $companyId) {
                $query->where('company_id', $companyId);
            })
            ->when($placementType, function ($query, $placementType) {
                $query->where('placement_type', $placementType);
            })
            ->when($isConfirmed !== null, function ($query) use ($isConfirmed) {
                $query->where('is_confirmed', $isConfirmed);
            })
            ->orderByDesc('offer_date')
            ->paginate(20);

        $departments = Department::all();
        $companies = Company::all();

        return view('reports.placements', compact(
            'placements',
            'departments',
            'companies',
            'departmentId',
            'companyId',
            'placementType',
            'isConfirmed'
        ));
    }

    /**
     * Display project report
     */
    public function projects(Request $request)
    {
        $this->authorize('viewAny', Project::class);

        // Get filters
        $departmentId = $request->input('department_id');
        $status = $request->input('status');
        $guideId = $request->input('guide_id');

        $projects = Project::with(['students.user.department', 'guide.department'])
            ->when($departmentId, function ($query, $departmentId) {
                $query->whereHas('students.user', function ($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($guideId, function ($query, $guideId) {
                $query->where('guide_id', $guideId);
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        $departments = Department::all();
        $guides = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'Faculty');
        })->get();

        return view('reports.projects', compact(
            'projects',
            'departments',
            'guides',
            'departmentId',
            'status',
            'guideId'
        ));
    }

    /**
     * Display evaluation report
     */
    public function evaluations(Request $request)
    {
        $this->authorize('viewAny', Evaluation::class);

        // Get filters
        $departmentId = $request->input('department_id');
        $projectId = $request->input('project_id');
        $grade = $request->input('grade');

        $evaluations = Evaluation::with(['student.user.department', 'project', 'guide'])
            ->when($departmentId, function ($query, $departmentId) {
                $query->whereHas('student.user', function ($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            })
            ->when($projectId, function ($query, $projectId) {
                $query->where('project_id', $projectId);
            })
            ->when($grade, function ($query, $grade) {
                $query->where('internal_exam_grade', $grade);
            })
            ->orderByDesc('evaluation_date')
            ->paginate(20);

        $departments = Department::all();
        $projects = Project::all();

        // Grade distribution
        $gradeDistribution = Evaluation::select('internal_exam_grade', DB::raw('count(*) as count'))
            ->when($departmentId, function ($query, $departmentId) {
                $query->whereHas('student.user', function ($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            })
            ->groupBy('internal_exam_grade')
            ->get();

        return view('reports.evaluations', compact(
            'evaluations',
            'departments',
            'projects',
            'gradeDistribution',
            'departmentId',
            'projectId',
            'grade'
        ));
    }
}
