<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{User, Student, Project, StudentPlacement, Evaluation, ReportLog, Company, Department};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $roles = $user->getRoleNames();

        if ($roles->contains('Admin')) return $this->adminDashboard();
        if ($roles->contains('TnP')) return $this->tnpDashboard();
        if ($roles->contains('Head')) return $this->hodDashboard($user);
        if ($roles->contains('Guide')) return $this->guideDashboard($user);
        if ($roles->contains('Student')) return $this->studentDashboard($user);

        return response()->json(['message' => 'No dashboard available for your role'], 403);
    }

    protected function adminDashboard()
    {
        return response()->json([
            'overview' => [
                'total_users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'total_students' => Student::count(),
                'total_departments' => Department::count(),
                'total_companies' => Company::count(),
                'total_projects' => Project::count(),
                'active_projects' => Project::where('status', 'IN_PROGRESS')->count(),
                'total_placements' => StudentPlacement::count(),
                'confirmed_placements' => StudentPlacement::where('is_confirmed', true)->count(),
            ],
            'recent_activities' => [
                'recent_projects' => Project::with(['guide', 'company'])->latest()->limit(5)
                    ->get(['id', 'project_id', 'title', 'status', 'guide_id', 'company_id', 'created_at']),
                'recent_placements' => StudentPlacement::with(['student.user', 'company'])->latest()->limit(5)
                    ->get(['id', 'student_id', 'company_id', 'job_title', 'package_lpa', 'offer_date']),
                'recent_reports' => ReportLog::with(['student.user'])->latest('submitted_date')->limit(5)
                    ->get(['id', 'student_id', 'title', 'report_type', 'status', 'submitted_date']),
            ],
            'statistics' => [
                'projects_by_status' => Project::selectRaw('status, count(*) as count')->groupBy('status')->get(),
                'placements_by_type' => StudentPlacement::selectRaw('placement_type, count(*) as count')->groupBy('placement_type')->get(),
                'students_by_training_status' => Student::selectRaw('training_status, count(*) as count')->groupBy('training_status')->get(),
            ],
        ]);
    }

    protected function tnpDashboard()
    {
        return response()->json([
            'overview' => [
                'total_students' => Student::count(),
                'students_in_training' => Student::where('training_status', 'IN_TRAINING')->count(),
                'students_completed' => Student::where('training_status', 'COMPLETED')->count(),
                'total_placements' => StudentPlacement::count(),
                'pending_confirmations' => StudentPlacement::where('is_confirmed', false)->count(),
                'average_package' => StudentPlacement::avg('package_lpa'),
                'highest_package' => StudentPlacement::max('package_lpa'),
            ],
            'projects' => [
                'total_projects' => Project::count(),
                'open_projects' => Project::where('status', 'OPEN')->count(),
                'in_progress' => Project::where('status', 'IN_PROGRESS')->count(),
                'completed' => Project::where('status', 'COMPLETED')->count(),
                'projects_needing_assignment' => Project::where('status', 'OPEN')->whereDoesntHave('students')->count(),
            ],
            'reports' => [
                'pending_review' => ReportLog::where('status', 'SUBMITTED')->count(),
                'reviewed' => ReportLog::where('status', 'REVIEWED')->count(),
                'approved' => ReportLog::where('status', 'APPROVED')->count(),
                'rejected' => ReportLog::where('status', 'REJECTED')->count(),
            ],
            'recent_placements' => StudentPlacement::with(['student.user', 'company'])->latest('offer_date')->limit(10)
                ->get(['id', 'student_id', 'company_id', 'job_title', 'package_lpa', 'placement_type', 'is_confirmed', 'offer_date']),
            'top_recruiters' => StudentPlacement::with('company')
                ->selectRaw('company_id, count(*) as placement_count, avg(package_lpa) as avg_package')
                ->groupBy('company_id')->orderByDesc('placement_count')->limit(5)->get(),
        ]);
    }

    protected function hodDashboard($user)
    {
        $deptId = $user->department_id;

        return response()->json([
            'overview' => [
                'department_students' => Student::where('department_id', $deptId)->count(),
                'department_projects' => Project::whereHas('students', fn($q) => $q->where('department_id', $deptId))->count(),
                'department_placements' => StudentPlacement::whereHas('student', fn($q) => $q->where('department_id', $deptId))->count(),
            ],
            'students' => [
                'by_batch' => Student::where('department_id', $deptId)->selectRaw('batch, count(*) as count')
                    ->groupBy('batch')->orderByDesc('batch')->get(),
                'by_training_status' => Student::where('department_id', $deptId)->selectRaw('training_status, count(*) as count')
                    ->groupBy('training_status')->get(),
            ],
            'placements' => [
                'total' => StudentPlacement::whereHas('student', fn($q) => $q->where('department_id', $deptId))->count(),
                'average_package' => StudentPlacement::whereHas('student', fn($q) => $q->where('department_id', $deptId))->avg('package_lpa'),
            ],
        ]);
    }

    protected function guideDashboard($user)
    {
        return response()->json([
            'overview' => [
                'my_projects' => Project::where('guide_id', $user->id)->count(),
                'active_projects' => Project::where('guide_id', $user->id)->where('status', 'IN_PROGRESS')->count(),
                'students_under_guidance' => DB::table('project_students')->join('projects', 'projects.id', '=', 'project_students.project_id')
                    ->where('projects.guide_id', $user->id)->distinct('project_students.student_id')->count(),
            ],
            'my_projects' => Project::where('guide_id', $user->id)->with(['company', 'students'])->withCount('students')->latest()
                ->get(['id', 'project_id', 'title', 'category', 'status', 'company_id', 'start_date', 'end_date']),
            'evaluations' => [
                'total' => Evaluation::where('evaluator_id', $user->id)->count(),
                'by_type' => Evaluation::where('evaluator_id', $user->id)
                    ->selectRaw('evaluation_type, count(*) as count, avg(marks_obtained) as avg_marks')
                    ->groupBy('evaluation_type')->get(),
            ],
            'pending_reports' => ReportLog::whereHas('project', fn($q) => $q->where('guide_id', $user->id))
                ->where('status', 'SUBMITTED')->with(['student.user', 'project'])->latest('submitted_date')->limit(10)
                ->get(['id', 'student_id', 'project_id', 'title', 'report_type', 'status', 'submitted_date']),
        ]);
    }

    protected function studentDashboard($user)
    {
        $student = Student::where('user_id', $user->id)->first();
        if (!$student) return response()->json(['message' => 'Student record not found'], 404);

        return response()->json([
            'profile' => [
                'roll_no' => $student->roll_no,
                'batch' => $student->batch,
                'department' => $student->department->name ?? null,
                'cgpa' => $student->cgpa,
                'training_status' => $student->training_status,
            ],
            'projects' => [
                'total' => $student->projects()->count(),
                'active' => $student->projects()->where('status', 'IN_PROGRESS')->count(),
                'my_projects' => $student->projects()->with(['guide', 'company'])
                    ->get(['id', 'project_id', 'title', 'category', 'status', 'guide_id', 'company_id']),
            ],
            'placements' => [
                'total_offers' => StudentPlacement::where('student_id', $student->id)->count(),
                'confirmed' => StudentPlacement::where('student_id', $student->id)->where('is_confirmed', true)->first(),
                'all_offers' => StudentPlacement::where('student_id', $student->id)->with('company')->latest('offer_date')
                    ->get(['id', 'company_id', 'job_title', 'package_lpa', 'placement_type', 'is_confirmed', 'offer_date']),
            ],
            'evaluations' => [
                'total' => Evaluation::where('student_id', $student->id)->count(),
                'average_marks' => Evaluation::where('student_id', $student->id)->avg('marks_obtained'),
                'by_type' => Evaluation::where('student_id', $student->id)
                    ->selectRaw('evaluation_type, avg(marks_obtained) as avg_marks, count(*) as count')->groupBy('evaluation_type')->get(),
                'recent' => Evaluation::where('student_id', $student->id)->with(['project', 'evaluator'])->latest('evaluation_date')->limit(5)
                    ->get(['id', 'project_id', 'evaluator_id', 'evaluation_type', 'marks_obtained', 'total_marks', 'grade', 'evaluation_date']),
            ],
            'reports' => [
                'total' => ReportLog::where('student_id', $student->id)->count(),
                'pending' => ReportLog::where('student_id', $student->id)->where('status', 'SUBMITTED')->count(),
                'approved' => ReportLog::where('student_id', $student->id)->where('status', 'APPROVED')->count(),
                'recent' => ReportLog::where('student_id', $student->id)->with('project')->latest('submitted_date')->limit(5)
                    ->get(['id', 'project_id', 'title', 'report_type', 'status', 'submitted_date']),
            ],
        ]);
    }
}
