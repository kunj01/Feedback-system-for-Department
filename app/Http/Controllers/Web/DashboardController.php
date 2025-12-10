<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index(Request $request)
    {
        $query = Student::with(['user', 'department', 'placements.company']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhere('roll_no', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Department filter
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        // Academic year filter
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Batch filter
        if ($request->filled('batch')) {
            $query->where('batch', $request->batch);
        }

        // Placed filter
        if ($request->filled('placed')) {
            if ($request->placed === 'yes') {
                $query->whereHas('placements', function ($q) {
                    $q->where('status', 'OFFERED');
                });
            } elseif ($request->placed === 'no') {
                $query->whereDoesntHave('placements', function ($q) {
                    $q->where('status', 'OFFERED');
                });
            }
        }

        // Eligible filter
        if ($request->filled('eligible')) {
            $query->where('is_eligible', $request->eligible);
        }

        // Training status filter
        if ($request->filled('training_status')) {
            $query->where('training_status', $request->training_status);
        }

        // CGPA filter
        if ($request->filled('min_cgpa')) {
            $query->where(function ($q) use ($request) {
                $q->where('cgpa', '>=', $request->min_cgpa)
                  ->orWhere('btech_cgpa_upto_5th', '>=', $request->min_cgpa);
            });
        }

        // Guide filter (for projects)
        if ($request->filled('guide')) {
            $query->whereHas('projects', function ($q) use ($request) {
                $q->where('guide_id', $request->guide);
            });
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        // Get filter options
        $departments = Department::all();
        $academicYears = Student::select('academic_year')
            ->distinct()
            ->whereNotNull('academic_year')
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        $batches = Student::select('batch')
            ->distinct()
            ->whereNotNull('batch')
            ->orderBy('batch', 'desc')
            ->pluck('batch');

        $guides = User::role('guide')->orderBy('name')->get();

        return view('dashboard', compact(
            'students',
            'departments',
            'academicYears',
            'batches',
            'guides'
        ));
    }
}
