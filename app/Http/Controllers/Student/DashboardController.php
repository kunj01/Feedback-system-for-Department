<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display student dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get student record
        $student = Student::with(['division', 'batchGroup', 'department'])
            ->where('user_id', $user->id)
            ->first();

        if (!$student) {
            return view('student.dashboard')->with('error', 'Student profile not found');
        }

        // Get student's timetable
        $timetable = null;
        if ($student->division_id) {
            $timetable = $this->getStudentTimetable($student);
        }

        // Get pending feedbacks
        $pendingFeedbacks = $this->getPendingFeedbacks($student);

        // Get student's subjects from timetable
        $subjects = $this->getStudentSubjects($student);

        return view('student.dashboard', compact(
            'student',
            'timetable',
            'pendingFeedbacks',
            'subjects'
        ));
    }

    /**
     * Get student timetable
     */
    private function getStudentTimetable(Student $student)
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $timeSlots = [
            '09:10-10:10',
            '10:10-11:10',
            '11:10-12:10',
            '12:10-01:10',
            '01:10-02:10',
            '02:20-03:20',
            '03:20-04:20',
        ];

        // Get all timetable entries for student's division
        $entries = Timetable::with(['subject', 'faculty', 'batch'])
            ->where('division_id', $student->division_id)
            ->where('is_active', true)
            ->where(function ($query) use ($student) {
                // Get lecture classes (no batch) OR classes for student's batch
                $query->whereNull('batch_id')
                    ->orWhere('batch_id', $student->batch_id);
            })
            ->get();

        $timetable = [];

        foreach ($timeSlots as $timeSlot) {
            $timetable[$timeSlot] = [];
            
            foreach ($days as $day) {
                $dayEntries = $entries->where('day', $day)
                    ->where('time_slot', $timeSlot)
                    ->values();
                
                $timetable[$timeSlot][$day] = $dayEntries;
            }
        }

        return [
            'days' => $days,
            'timeSlots' => $timeSlots,
            'timetable' => $timetable,
        ];
    }

    /**
     * Get pending feedbacks for student
     */
    private function getPendingFeedbacks(Student $student)
    {
        if (!$student->division_id) {
            return collect();
        }

        // Get form assignments for student's division and batch
        $assignments = DB::table('form_assignments')
            ->join('subjects', 'form_assignments.subject_id', '=', 'subjects.id')
            ->join('teachers', 'form_assignments.teacher_id', '=', 'teachers.id')
            ->where('form_assignments.is_active', true)
            ->where(function ($query) use ($student) {
                $query->where('form_assignments.division_id', $student->division_id)
                    ->where(function ($q) use ($student) {
                        $q->whereNull('form_assignments.batch_id')
                          ->orWhere('form_assignments.batch_id', $student->batch_id);
                    });
            })
            ->select(
                'form_assignments.id',
                'form_assignments.subject_id',
                'form_assignments.teacher_id',
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'teachers.name as teacher_name',
                'form_assignments.start_date',
                'form_assignments.end_date'
            )
            ->get();

        // Filter out completed feedbacks
        $pendingFeedbacks = $assignments->filter(function ($assignment) use ($student) {
            $submitted = DB::table('form_responses')
                ->where('form_assignment_id', $assignment->id)
                ->where('student_id', $student->id)
                ->exists();
            
            return !$submitted;
        });

        return $pendingFeedbacks;
    }

    /**
     * Get student's subjects from timetable
     */
    private function getStudentSubjects(Student $student)
    {
        if (!$student->division_id) {
            return collect();
        }

        $subjectIds = Timetable::where('division_id', $student->division_id)
            ->where('is_active', true)
            ->where(function ($query) use ($student) {
                $query->whereNull('batch_id')
                    ->orWhere('batch_id', $student->batch_id);
            })
            ->distinct()
            ->pluck('subject_id');

        return DB::table('subjects')
            ->whereIn('id', $subjectIds)
            ->where('is_active', true)
            ->orderBy('subject_name')
            ->get();
    }

    /**
     * Get student's timetable view (separate page)
     */
    public function timetable()
    {
        $user = Auth::user();
        $student = Student::with(['division', 'batchGroup'])
            ->where('user_id', $user->id)
            ->first();

        if (!$student || !$student->division_id) {
            return view('student.timetable')
                ->with('error', 'Timetable not available');
        }

        $timetableData = $this->getStudentTimetable($student);

        return view('student.timetable', array_merge(
            compact('student'),
            $timetableData
        ));
    }
}
