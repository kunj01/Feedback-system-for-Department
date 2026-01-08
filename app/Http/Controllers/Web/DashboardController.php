<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Department;
use App\Models\User;
use App\Models\FormAssignment;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is admin
        if ($user->hasRole('Admin')) {
            return $this->adminDashboard($request);
        }
        
        // Check if user is faculty
        if ($user->hasRole('Faculty')) {
            return $this->facultyDashboard();
        }
        
        // Check if user is student
        if ($user->hasRole('Student')) {
            return $this->studentDashboard();
        }
        
        // Default fallback
        return view('dashboard');
    }
    
    /**
     * Admin Dashboard
     */
    private function adminDashboard(Request $request)
    {
        // Get all forms from documents folder
        $formsPath = public_path('documents');
        $totalForms = 0;
        
        if (File::exists($formsPath)) {
            $files = File::files($formsPath);
            $totalForms = count($files);
        }
        
        // Get assignment statistics
        $totalAssignments = FormAssignment::count();
        $pendingAssignments = FormAssignment::where('status', 'pending')->count();
        $completedAssignments = FormAssignment::where('status', 'completed')->count();
        
        // Get total students
        $totalStudents = Student::count();
        
        // Get recent assignments
        $recentAssignments = FormAssignment::with(['student.user', 'teacher', 'subject'])
            ->latest()
            ->take(10)
            ->get();
        
        // Pass isAdmin flag
        $isAdmin = true;
        
        return view('dashboard', compact(
            'totalForms',
            'totalAssignments',
            'pendingAssignments',
            'completedAssignments',
            'totalStudents',
            'recentAssignments',
            'isAdmin'
        ));
    }
    
    /**
     * Student Dashboard
     */
    private function studentDashboard()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            $assignedForms = collect([]);
            return view('dashboard', compact('assignedForms'));
        }
        
        // Get assigned forms grouped by form_name
        $assignedForms = FormAssignment::with(['teacher', 'subject'])
            ->where('student_id', $student->id)
            ->get();
        
        // Pass isAdmin flag
        $isAdmin = false;
        
        return view('dashboard', compact('assignedForms', 'isAdmin'));
    }
    
    /**
     * Faculty Dashboard
     */
    private function facultyDashboard()
    {
        $totalSpeakers = Speaker::where('created_by', auth()->id())->count();
        $recentSpeakers = Speaker::where('created_by', auth()->id())
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();
        
        $isFaculty = true;
        
        return view('dashboard', compact('totalSpeakers', 'recentSpeakers', 'isFaculty'));
    }
}
