<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\FeedbackAssignment;
use Illuminate\Http\Request;

class FeedbackAssignmentController extends Controller
{
    // List of available subjects (same as in dashboard)
    private $subjects = [
        1 => 'Data Structures',
        2 => 'Operating Systems',
        3 => 'Database Management',
        4 => 'Computer Networks',
        5 => 'Software Engineering'
    ];

    public function index()
    {
        $students = Student::with('user')->get();
        $assignments = FeedbackAssignment::with('student.user')->get();
        
        return view('admin.feedback.assignments', [
            'students' => $students,
            'subjects' => $this->subjects,
            'assignments' => $assignments
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'required|integer|between:1,5',
            'academic_year' => 'required|string|max:20'
        ]);

        $created = 0;
        foreach ($request->subject_ids as $subjectId) {
            $assignment = FeedbackAssignment::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'subject_id' => $subjectId,
                    'academic_year' => $request->academic_year
                ]
            );
            if ($assignment->wasRecentlyCreated) {
                $created++;
            }
        }

        return redirect()->back()->with('success', "Assigned {$created} subject(s) successfully!");
    }

    public function destroy($id)
    {
        $assignment = FeedbackAssignment::findOrFail($id);
        $assignment->delete();

        return redirect()->back()->with('success', 'Assignment removed successfully!');
    }
}
