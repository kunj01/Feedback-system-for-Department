<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\FormAssignment;
use App\Models\Student;

class FormController extends Controller
{
    /**
     * Display a listing of all forms (Admin view - for assignment).
     */
    public function index(Request $request)
    {
        // Get search query
        $search = $request->get('search', '');

        // If admin, show all forms for assignment
        if (auth()->user()->hasRole('Admin')) {
            // Get all files from public/documents directory
            $formsPath = public_path('documents');
            $forms = [];

            if (File::exists($formsPath)) {
                $files = File::files($formsPath);
                
                foreach ($files as $file) {
                    $fileName = $file->getFilename();
                    $extension = $file->getExtension();
                    
                    // Filter by search if provided
                    if ($search && stripos($fileName, $search) === false) {
                        continue;
                    }

                    // Count assignments for this form
                    $assignmentCount = FormAssignment::where('form_name', $fileName)->count();
                    $pendingCount = FormAssignment::where('form_name', $fileName)->pending()->count();
                    $completedCount = FormAssignment::where('form_name', $fileName)->completed()->count();

                    // Get file info and clean display name
                    $displayName = pathinfo($fileName, PATHINFO_FILENAME);
                    // Remove timestamp prefix (e.g., "1766770674_")
                    $displayName = preg_replace('/^\d+_/', '', $displayName);
                    // Remove leading numbers with dot/space (e.g., "2. " or "4. ")
                    $displayName = preg_replace('/^\d+\.\s*/', '', $displayName);
                    
                    $forms[] = [
                        'name' => $fileName,
                        'display_name' => $displayName,
                        'extension' => strtoupper($extension),
                        'size' => $this->formatBytes($file->getSize()),
                        'modified' => date('M d, Y H:i', $file->getMTime()),
                        'path' => 'documents/' . $fileName,
                        'icon' => $this->getFileIcon($extension),
                        'color' => $this->getFileColor($extension),
                        'assignment_count' => $assignmentCount,
                        'pending_count' => $pendingCount,
                        'completed_count' => $completedCount,
                    ];
                }
            }

            // Sort by name
            usort($forms, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });

            return view('admin.forms.index', compact('forms', 'search'));
        }

        // For students, show only assigned forms
        $student = auth()->user()->student;
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'No student profile found.');
        }

        $assignments = FormAssignment::where('student_id', $student->id)
            ->with(['teacher', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Group assignments by form_name to consolidate multi-teacher forms
        $groupedAssignments = $assignments->groupBy('form_name');

        return view('student.forms.index', compact('assignments', 'groupedAssignments'));
    }

    /**
     * Show the form upload page (Admin only).
     */
    public function create()
    {
        // Only admins can upload forms
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');
        
        return view('admin.forms.create');
    }

    /**
     * Store a newly uploaded form (Admin only).
     */
    public function store(Request $request)
    {
        // Only admins can upload forms
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');

        $request->validate([
            'form_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // 10MB max
        ]);

        if ($request->hasFile('form_file')) {
            $file = $request->file('form_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Move to public/documents directory
            $file->move(public_path('documents'), $fileName);

            return redirect()->route('forms.index')
                ->with('success', 'Form uploaded successfully!');
        }

        return back()->with('error', 'Failed to upload form.');
    }

    /**
     * View form details and assign to students (Admin).
     * Or display the form for student to fill.
     */
    public function show($filename)
    {
        $filePath = public_path('documents/' . $filename);

        if (!File::exists($filePath)) {
            abort(404, 'Form not found.');
        }

        // Extract form title from filename
        $formTitle = pathinfo($filename, PATHINFO_FILENAME);
        $formTitle = preg_replace('/^\d+_/', '', $formTitle); // Remove timestamp prefix
        $formTitle = ucwords(str_replace('_', ' ', $formTitle));

        // If admin, show assignment interface
        if (auth()->user()->hasRole('Admin')) {
            // Get all students
            $students = Student::with('user')->get();
            
            // Get assignments for this form
            $assignments = FormAssignment::where('form_name', $filename)
                ->with('student.user', 'subject', 'teacher')
                ->get();

            // Get active subjects with their teachers
            $subjects = \App\Models\Subject::where('is_active', true)
                ->with('teachers')
                ->get();

            return view('admin.forms.assign', [
                'formTitle' => $formTitle,
                'formName' => $filename,
                'students' => $students,
                'assignments' => $assignments,
                'subjects' => $subjects,
            ]);
        }

        // For students, check if the form is assigned to them
        $student = auth()->user()->student;
        if (!$student) {
            abort(403, 'No student profile found.');
        }

        // Get all assignments for this form (may be multiple for multi-teacher)
        $allAssignments = FormAssignment::with(['teacher', 'subject'])
            ->where('form_name', $filename)
            ->where('student_id', $student->id)
            ->get();
            
        if ($allAssignments->isEmpty()) {
            abort(404, 'Form assignment not found.');
        }
        
        // Use first assignment as default
        $assignment = $allAssignments->first();

        // Check if form is active (within feedback period)
        if (!$assignment->isActive()) {
            if ($assignment->isUpcoming()) {
                return redirect()->route('forms.index')
                    ->with('info', 'This form will be available from ' . $assignment->start_date->format('M d, Y H:i'));
            }
            
            if ($assignment->hasEnded()) {
                return redirect()->route('forms.index')
                    ->with('error', 'The submission deadline for this form has passed.');
            }
        }

        // If already completed, show message
        if ($assignment->status === 'completed') {
            return redirect()->route('forms.index')
                ->with('info', 'You have already submitted this form.');
        }

        // Check if this is the Curriculum Feedback or Academic-Teacher-Industry form
        if (stripos($filename, 'Academic-Teacher-Industry') !== false) {
            return view('student.forms.curriculum-feedback', [
                'formTitle' => $formTitle,
                'formName' => $filename,
                'assignment' => $assignment,
                'allAssignments' => $allAssignments,
            ]);
        }

        return view('student.forms.fill', [
            'formTitle' => $formTitle,
            'formName' => $filename,
            'assignment' => $assignment,
            'allAssignments' => $allAssignments,
        ]);
    }

    /**
     * Assign form to students (Admin only).
     */
    public function assign(Request $request, $filename)
    {
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');

        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'grace_period_hours' => 'nullable|integer|min:0|max:168',
            'is_multi_teacher' => 'nullable|boolean',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
            'teacher_ids' => 'nullable|array',
        ]);

        $filePath = public_path('documents/' . $filename);
        if (!File::exists($filePath)) {
            abort(404, 'Form not found.');
        }

        // Extract form title
        $formTitle = pathinfo($filename, PATHINFO_FILENAME);
        $formTitle = preg_replace('/^\d+_/', '', $formTitle);
        $formTitle = ucwords(str_replace('_', ' ', $formTitle));

        $assignedCount = 0;
        $isMultiTeacher = $request->has('is_multi_teacher');
        
        if ($isMultiTeacher && $request->has('subject_ids') && $request->has('teacher_ids')) {
            // Multi-teacher mode: Create assignments for each teacher-student combination
            foreach ($request->student_ids as $studentId) {
                foreach ($request->subject_ids as $subjectId) {
                    if (isset($request->teacher_ids[$subjectId])) {
                        foreach ($request->teacher_ids[$subjectId] as $teacherId) {
                            $assignment = FormAssignment::firstOrCreate(
                                [
                                    'form_name' => $filename,
                                    'student_id' => $studentId,
                                    'subject_id' => $subjectId,
                                    'teacher_id' => $teacherId,
                                ],
                                [
                                    'form_title' => $formTitle,
                                    'assigned_by' => auth()->id(),
                                    'start_date' => $request->start_date,
                                    'end_date' => $request->end_date,
                                    'grace_period_hours' => $request->grace_period_hours ?? 0,
                                    'is_multi_teacher' => true,
                                ]
                            );

                            if ($assignment->wasRecentlyCreated) {
                                $assignedCount++;
                            }
                        }
                    }
                }
            }
        } else {
            // Regular mode: One assignment per student
            foreach ($request->student_ids as $studentId) {
                $assignment = FormAssignment::firstOrCreate(
                    [
                        'form_name' => $filename,
                        'student_id' => $studentId,
                        'is_multi_teacher' => false,
                    ],
                    [
                        'form_title' => $formTitle,
                        'assigned_by' => auth()->id(),
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'grace_period_hours' => $request->grace_period_hours ?? 0,
                    ]
                );

                if ($assignment->wasRecentlyCreated) {
                    $assignedCount++;
                }
            }
        }

        return redirect()->route('forms.show', $filename)
            ->with('success', "Form assigned to $assignedCount assignment(s) successfully!");
    }

    /**
     * Submit form (Student only).
     */
    public function submit(Request $request, $filename)
    {
        $student = auth()->user()->student;
        if (!$student) {
            abort(403, 'No student profile found.');
        }

        // Get the specific assignment (especially important for multi-teacher forms)
        if ($request->has('teacher_assignment_id')) {
            $assignment = FormAssignment::where('id', $request->teacher_assignment_id)
                ->where('student_id', $student->id)
                ->where('form_name', $filename)
                ->firstOrFail();
        } else {
            $assignment = FormAssignment::where('form_name', $filename)
                ->where('student_id', $student->id)
                ->firstOrFail();
        }

        // Check if form is still active
        if (!$assignment->isActive()) {
            return redirect()->route('forms.index')
                ->with('error', 'The submission period for this form has ended.');
        }

        // Validate form data
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string|max:255',
            'responses' => 'required|array',
            'responses.*' => 'required|in:excellent,very_good,good,average,below_average',
            'comments_strengths' => 'nullable|string|max:1000',
            'comments_improvements' => 'nullable|string|max:1000',
            'comments_other' => 'nullable|string|max:1000',
        ]);

        // Merge comments into responses
        $allResponses = $validated['responses'];
        if (!empty($validated['comments_strengths'])) {
            $allResponses['comments_strengths'] = $validated['comments_strengths'];
        }
        if (!empty($validated['comments_improvements'])) {
            $allResponses['comments_improvements'] = $validated['comments_improvements'];
        }
        if (!empty($validated['comments_other'])) {
            $allResponses['comments_other'] = $validated['comments_other'];
        }

        // Create form response
        \App\Models\FormResponse::create([
            'form_assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'email' => $validated['email'],
            'name' => $validated['name'],
            'responses' => $allResponses,
        ]);

        // Store feedback in storage/app/form_submissions directory for backup
        $submissionDir = storage_path('app/form_submissions');
        if (!File::exists($submissionDir)) {
            File::makeDirectory($submissionDir, 0755, true);
        }

        // Create submission data
        $submissionData = [
            'form_name' => $filename,
            'student_id' => $student->id,
            'student_name' => auth()->user()->name,
            'student_email' => auth()->user()->email,
            'responses' => $validated['responses'],
            'submitted_at' => now()->toDateTimeString(),
        ];

        // Save to JSON file
        $submissionFile = $submissionDir . '/' . time() . '_' . $student->id . '_' . $filename . '.json';
        File::put($submissionFile, json_encode($submissionData, JSON_PRETTY_PRINT));

        // Mark assignment as completed
        $assignment->markAsCompleted();

        return redirect()->route('forms.index')
            ->with('success', 'Thank you! Your form has been submitted successfully.');
    }

    /**
     * Download a form file.
     */
    public function download($filename)
    {
        $filePath = public_path('documents/' . $filename);

        if (!File::exists($filePath)) {
            abort(404, 'Form not found.');
        }

        return response()->download($filePath);
    }

    /**
     * Delete a form file (Admin only).
     */
    public function destroy($filename)
    {
        // Only admins can delete forms
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');

        $filePath = public_path('documents/' . $filename);

        if (File::exists($filePath)) {
            // Delete all assignments for this form
            FormAssignment::where('form_name', $filename)->delete();
            
            // Delete the file
            File::delete($filePath);
            
            return redirect()->route('forms.index')
                ->with('success', 'Form deleted successfully!');
        }

        return back()->with('error', 'Form not found.');
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Get icon SVG path for file type.
     */
    private function getFileIcon($extension)
    {
        $icons = [
            'pdf' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
            'doc' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'docx' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'xls' => 'M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
            'xlsx' => 'M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
        ];

        return $icons[$extension] ?? 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
    }

    /**
     * Get color class for file type.
     */
    private function getFileColor($extension)
    {
        $colors = [
            'pdf' => 'red',
            'doc' => 'blue',
            'docx' => 'blue',
            'xls' => 'green',
            'xlsx' => 'green',
        ];

        return $colors[$extension] ?? 'gray';
    }

    /**
     * Save multi-teacher configuration for a form.
     */
    public function saveMultiTeacherConfig(Request $request)
    {
        \Log::info('Multi-teacher config request:', $request->all());
        
        $request->validate([
            'form_name' => 'required|string',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_ids' => 'required|array',
            'teacher_ids.*' => 'integer|exists:teachers,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'grace_period_hours' => 'nullable|integer|min:0|max:168',
        ]);

        $filename = $request->form_name;
        $subjectId = $request->subject_id;
        $teacherIds = $request->teacher_ids;
        
        \Log::info('Processing multi-teacher:', [
            'filename' => $filename,
            'subject_id' => $subjectId,
            'teacher_ids' => $teacherIds,
            'student_ids' => $request->student_ids
        ]);
        
        // Extract form title
        $formTitle = pathinfo($filename, PATHINFO_FILENAME);
        $formTitle = preg_replace('/^\d+_/', '', $formTitle);
        $formTitle = ucwords(str_replace('_', ' ', $formTitle));

        $assignedCount = 0;
        
        // Create assignments for each teacher-student combination
        foreach ($request->student_ids as $studentId) {
            foreach ($teacherIds as $teacherId) {
                $assignment = FormAssignment::updateOrCreate(
                    [
                        'form_name' => $filename,
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'teacher_id' => $teacherId,
                    ],
                    [
                        'form_title' => $formTitle,
                        'assigned_by' => auth()->id(),
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'grace_period_hours' => $request->grace_period_hours ?? 0,
                        'is_multi_teacher' => true,
                        'status' => 'pending',
                    ]
                );

                if ($assignment->wasRecentlyCreated) {
                    $assignedCount++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Multi-teacher configuration saved! Created {$assignedCount} new assignment(s).",
            'assignments_count' => $assignedCount
        ]);
    }

}
