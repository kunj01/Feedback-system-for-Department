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

            // Get active subjects with their teachers (ordered by semester and sort_order)
            $subjects = \App\Models\Subject::where('is_active', true)
                ->with('teachers')
                ->orderBy('semester', 'asc')
                ->orderBy('sort_order', 'asc')
                ->get();

            // Get multi-teacher mode status from system settings
            $multiTeacherModeEnabled = \App\Models\SystemSettings::get('multi_teacher_feedback_mode', false);

            return view('admin.forms.assign', [
                'formTitle' => $formTitle,
                'formName' => $filename,
                'students' => $students,
                'assignments' => $assignments,
                'subjects' => $subjects,
                'multiTeacherModeEnabled' => $multiTeacherModeEnabled,
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
        
        // Use first pending assignment as default, or first assignment if all completed
        $assignment = $allAssignments->where('status', 'pending')->first() ?? $allAssignments->first();

        // Check if ALL assignments are completed (for multi-teacher forms)
        $allCompleted = $allAssignments->every(function($a) {
            return $a->status === 'completed';
        });

        // If all assignments are completed, show message
        if ($allCompleted) {
            return redirect()->route('forms.index')
                ->with('info', 'You have already submitted this form for all assigned teachers.');
        }

        // Check if form is active (within feedback period)
        // Use first pending assignment to check deadline
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

        // Check if this is the Curriculum Feedback or Academic-Teacher-Industry form
        if (stripos($filename, 'Academic-Teacher-Industry') !== false) {
            return view('student.forms.curriculum-feedback', [
                'formTitle' => $formTitle,
                'formName' => $filename,
                'assignment' => $assignment,
                'allAssignments' => $allAssignments,
            ]);
        }

        // Check if this is the Student Feedback form
        if (stripos($filename, 'Student-Feedback') !== false || stripos($filename, 'student-feedback') !== false) {
            // Calculate progress for this student
            $totalAssignments = FormAssignment::where('student_id', $student->id)->count();
            $completedAssignments = FormAssignment::where('student_id', $student->id)->where('status', 'completed')->count();
            $pendingAssignments = FormAssignment::where('student_id', $student->id)->where('status', 'pending')->count();
            
            return view('student.forms.student-feedback-form', [
                'formTitle' => $formTitle,
                'formName' => $filename,
                'assignment' => $assignment,
                'allAssignments' => $allAssignments,
                'totalAssignments' => $totalAssignments,
                'completedAssignments' => $completedAssignments,
                'pendingAssignments' => $pendingAssignments,
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
        \Log::info('=== FORM SUBMISSION STARTED ===', [
            'filename' => $filename,
            'user_id' => auth()->id(),
            'timestamp' => now()->toDateTimeString(),
            'ip' => $request->ip(),
            'request_data' => $request->except(['_token'])
        ]);

        try {
            $student = auth()->user()->student;
            if (!$student) {
                \Log::error('✗ Student profile not found', ['user_id' => auth()->id()]);
                abort(403, 'No student profile found.');
            }

            \Log::info('✓ Student found', ['student_id' => $student->id, 'name' => auth()->user()->name]);

            // Get the specific assignment (especially important for multi-teacher forms)
            if ($request->has('teacher_assignment_id')) {
                \Log::info('Looking for teacher assignment', ['teacher_assignment_id' => $request->teacher_assignment_id]);
                $assignment = FormAssignment::where('id', $request->teacher_assignment_id)
                    ->where('student_id', $student->id)
                    ->where('form_name', $filename)
                    ->firstOrFail();
            } else {
                \Log::info('Looking for general assignment');
                $assignment = FormAssignment::where('form_name', $filename)
                    ->where('student_id', $student->id)
                    ->firstOrFail();
            }

            \Log::info('✓ Assignment found', [
                'assignment_id' => $assignment->id,
                'status' => $assignment->status,
                'is_multi_teacher' => $assignment->is_multi_teacher
            ]);

            // Check if already submitted
            $existingResponse = \App\Models\FormResponse::where('form_assignment_id', $assignment->id)->first();
            if ($existingResponse) {
                \Log::warning('✗ Form already submitted', [
                    'assignment_id' => $assignment->id,
                    'response_id' => $existingResponse->id
                ]);
                return redirect()->route('forms.show', $filename)
                    ->with('info', 'You have already submitted this form for this teacher.');
            }

            // Check if form is still active
            if (!$assignment->isActive()) {
                \Log::warning('✗ Assignment not active', [
                    'assignment_id' => $assignment->id,
                    'start_date' => $assignment->start_date,
                    'end_date' => $assignment->end_date
                ]);
                return redirect()->route('forms.show', $filename)
                    ->with('error', 'The submission period for this form has ended.');
            }

            \Log::info('✓ Assignment is active', ['assignment_id' => $assignment->id]);

            // Validate form data - handle both curriculum feedback and other forms
            \Log::info('Starting validation...', ['form_name' => $filename]);
            
            // Check if this is curriculum feedback form (has different validation rules)
            $isCurriculumFeedback = stripos($filename, 'Academic-Teacher-Industry') !== false || 
                                   stripos($filename, 'Curriculum') !== false;
            
            // Check if this is student feedback form
            $isStudentFeedback = stripos($filename, 'Student-Feedback') !== false || 
                                stripos($filename, 'student-feedback') !== false;
            
            if ($isCurriculumFeedback) {
                \Log::info('Validating as Curriculum Feedback form');
                $validated = $request->validate([
                    'teacher_assignment_id' => 'required|exists:form_assignments,id',
                    'responses' => 'required|array',
                    'responses.program' => 'required|string|max:255',
                    'responses.course' => 'nullable|string|max:255',
                    'responses.content_of_syllabus' => 'required|integer|min:1|max:5',
                    'responses.relevance_to_industry' => 'required|integer|min:1|max:5',
                    'responses.course_outcomes_defined' => 'required|integer|min:1|max:5',
                    'responses.reading_materials_resources' => 'required|integer|min:1|max:5',
                    'responses.advanced_topics' => 'required|integer|min:1|max:5',
                    'responses.pedagogy_proposed' => 'required|integer|min:1|max:5',
                    'responses.theory_practical_balance' => 'required|integer|min:1|max:5',
                    'responses.assessment_methods' => 'required|integer|min:1|max:5',
                    'responses.project_component' => 'required|integer|min:1|max:5',
                    'responses.industrial_training' => 'required|integer|min:1|max:5',
                    'responses.additional_suggestions' => 'nullable|string|max:2000',
                ]);
            } elseif ($isStudentFeedback) {
                \Log::info('Validating as Student Feedback form');
                $validated = $request->validate([
                    'responses' => 'required|array',
                    // Section 1 - Student experience (5 questions)
                    'responses.prepare_for_class.rating' => 'required|string',
                    'responses.ask_questions_freely.rating' => 'required|string',
                    'responses.actively_participate.rating' => 'required|string',
                    'responses.feel_comfortable_sharing.rating' => 'required|string',
                    'responses.developing_skills.rating' => 'required|string',
                    // Section 2 - Instructor experience (8 questions)
                    'responses.instructor_approachable.rating' => 'required|string',
                    'responses.instructor_effective.rating' => 'required|string',
                    'responses.presentations_clear.rating' => 'required|string',
                    'responses.instructor_stimulated.rating' => 'required|string',
                    'responses.instructor_used_time.rating' => 'required|string',
                    'responses.instructor_introduces_concepts.rating' => 'required|string',
                    'responses.instructor_positive_environment.rating' => 'required|string',
                    'responses.instructor_communicates.rating' => 'required|string',
                    // Section 3 - Course content (7 questions)
                    'responses.learning_objectives_clear.rating' => 'required|string',
                    'responses.content_organized.rating' => 'required|string',
                    'responses.opportunities_practice.rating' => 'required|string',
                    'responses.access_materials.rating' => 'required|string',
                    'responses.content_prepares.rating' => 'required|string',
                    'responses.teaching_assessments.rating' => 'required|string',
                    'responses.diverse_perspectives.rating' => 'required|string',
                    // Optional reasoning fields for "Strongly Disagree" ratings
                    'responses.*.reasoning' => 'nullable|string|max:1000',
                    // Section 4 - Open-ended questions (3 questions)
                    'responses.most_useful' => 'required|string|max:2000',
                    'responses.missing_topics' => 'required|string|max:2000',
                    'responses.improvement_suggestions' => 'required|string|max:2000',
                ]);
            } else {
                \Log::info('Validating as generic form');
                $validated = $request->validate([
                    'email' => 'required|email',
                    'name' => 'nullable|string|max:255',
                    'responses' => 'required|array',
                    'responses.*' => 'required|in:excellent,very_good,good,average,below_average',
                    'comments_strengths' => 'nullable|string|max:1000',
                    'comments_improvements' => 'nullable|string|max:1000',
                    'comments_other' => 'nullable|string|max:1000',
                ]);
            }

            \Log::info('✓ Validation passed', [
                'responses_count' => count($validated['responses'] ?? []),
                'is_curriculum_feedback' => $isCurriculumFeedback
            ]);

            // Prepare response data based on form type
            if ($isCurriculumFeedback) {
                $allResponses = $validated['responses'];
            } else {
                // Merge comments into responses for generic forms
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
            }

            \Log::info('Creating form response in database...');

            // Create form response with transaction
            \DB::beginTransaction();
            
            try {
                $formResponseData = [
                    'form_assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                    'responses' => $allResponses,
                ];
                
                // Add email and name for generic forms (not curriculum or student feedback)
                if (!$isCurriculumFeedback && !$isStudentFeedback) {
                    $formResponseData['email'] = $validated['email'];
                    $formResponseData['name'] = $validated['name'] ?? auth()->user()->name;
                } else {
                    $formResponseData['email'] = auth()->user()->email;
                    $formResponseData['name'] = auth()->user()->name;
                }
                
                $formResponse = \App\Models\FormResponse::create($formResponseData);

                \Log::info('✓✓✓ FORM RESPONSE CREATED IN DATABASE ✓✓✓', [
                    'form_response_id' => $formResponse->id,
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id
                ]);

                // Store feedback in storage/app/form_submissions directory for backup
                $submissionDir = storage_path('app/form_submissions');
                if (!File::exists($submissionDir)) {
                    File::makeDirectory($submissionDir, 0755, true);
                }

                // Create submission data
                $submissionData = [
                    'form_response_id' => $formResponse->id,
                    'form_name' => $filename,
                    'student_id' => $student->id,
                    'student_name' => auth()->user()->name,
                    'student_email' => auth()->user()->email,
                    'responses' => $allResponses,
                    'submitted_at' => now()->toDateTimeString(),
                ];

                // Save to JSON file
                $submissionFile = $submissionDir . '/' . time() . '_' . $student->id . '_' . $filename . '.json';
                File::put($submissionFile, json_encode($submissionData, JSON_PRETTY_PRINT));

                \Log::info('✓ Backup JSON file created', ['file' => basename($submissionFile)]);

                // Mark assignment as completed
                $assignment->markAsCompleted();

                \Log::info('✓ Assignment marked as completed', ['assignment_id' => $assignment->id]);

                \DB::commit();

                \Log::info('✓✓✓ FORM SUBMISSION COMPLETED SUCCESSFULLY ✓✓✓');

                // Redirect back to the same form page to update progress bar
                return redirect()->route('forms.show', $filename)
                    ->with('success', 'Thank you! Your form has been submitted successfully. Progress updated.');

            } catch (\Exception $e) {
                \DB::rollback();
                \Log::error('✗✗✗ DATABASE ERROR DURING FORM SUBMISSION ✗✗✗', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('✗ Validation failed', [
                'errors' => $e->errors()
            ]);
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('✗✗✗ UNEXPECTED ERROR IN FORM SUBMISSION ✗✗✗', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Failed to submit form. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
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
     * View all responses for a form (Admin only).
     */
    public function responses($filename)
    {
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');

        $filePath = public_path('documents/' . $filename);
        if (!File::exists($filePath)) {
            abort(404, 'Form not found.');
        }

        // Get all responses for this form with student and assignment info
        $responses = \App\Models\FormResponse::whereHas('formAssignment', function($query) use ($filename) {
            $query->where('form_name', $filename);
        })
        ->with(['student.user', 'formAssignment.teacher', 'formAssignment.subject'])
        ->latest()
        ->paginate(20);

        // Extract form title
        $formTitle = pathinfo($filename, PATHINFO_FILENAME);
        $formTitle = preg_replace('/^\d+_/', '', $formTitle);
        $formTitle = ucwords(str_replace('_', ' ', $formTitle));

        // Calculate statistics
        $totalResponses = $responses->total();
        $totalAssignments = FormAssignment::where('form_name', $filename)->count();
        $responseRate = $totalAssignments > 0 ? round(($totalResponses / $totalAssignments) * 100, 1) : 0;

        return view('admin.forms.responses', compact('responses', 'filename', 'formTitle', 'totalResponses', 'totalAssignments', 'responseRate'));
    }

    /**
     * View individual response details (Admin only).
     */
    public function viewResponse($id)
    {
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');

        $response = \App\Models\FormResponse::with(['student.user', 'formAssignment.teacher', 'formAssignment.subject'])
            ->findOrFail($id);

        return view('admin.forms.view-response', compact('response'));
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
