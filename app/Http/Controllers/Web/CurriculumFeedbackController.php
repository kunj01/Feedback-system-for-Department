<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CurriculumFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurriculumFeedbackController extends Controller
{
    /**
     * Display a listing of curriculum feedbacks (Admin view).
     */
    public function index(Request $request)
    {
        // Only admins can view all feedbacks
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');

        $query = CurriculumFeedback::query()->with('user');

        // Filter by respondent type
        if ($request->filled('respondent_type')) {
            $query->where('respondent_type', $request->respondent_type);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by institute, email, or program
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('institute', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('program', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%");
            });
        }

        $feedbacks = $query->latest()->paginate(20);

        // Get statistics
        $stats = [
            'total' => CurriculumFeedback::count(),
            'academician' => CurriculumFeedback::where('respondent_type', 'academician')->count(),
            'teacher' => CurriculumFeedback::where('respondent_type', 'teacher')->count(),
            'industry' => CurriculumFeedback::where('respondent_type', 'industry')->count(),
        ];

        // Get unique academic years for filter
        $academicYears = CurriculumFeedback::distinct()->pluck('academic_year')->filter();

        return view('admin.curriculum-feedback.index', compact('feedbacks', 'stats', 'academicYears'));
    }

    /**
     * Show the form for creating new feedback (Public form).
     * This is specifically for "Feedback On Curriculum (Academic-Teacher-Industry)" form.
     */
    public function create(Request $request)
    {
        return view('admin.curriculum-feedback.create');
    }

    /**
     * Store a newly created feedback in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'respondent_type' => 'required|in:academician,teacher,industry',
            'institute' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'program' => 'required|string|max:255',
            'course' => 'nullable|string|max:255',
            'content_of_syllabus' => 'required|integer|min:1|max:5',
            'relevance_to_industry' => 'required|integer|min:1|max:5',
            'course_outcomes_defined' => 'required|integer|min:1|max:5',
            'reading_materials_resources' => 'required|integer|min:1|max:5',
            'advanced_topics' => 'required|integer|min:1|max:5',
            'pedagogy_proposed' => 'required|integer|min:1|max:5',
            'theory_practical_balance' => 'required|integer|min:1|max:5',
            'assessment_methods' => 'required|integer|min:1|max:5',
            'project_component' => 'required|integer|min:1|max:5',
            'industrial_training' => 'required|integer|min:1|max:5',
            'additional_suggestions' => 'nullable|string',
            'academic_year' => 'nullable|string|max:50',
        ]);

        // Add metadata
        $validated['user_id'] = auth()->check() ? auth()->id() : null;
        $validated['ip_address'] = $request->ip();
        $validated['status'] = 'submitted';

        $feedback = CurriculumFeedback::create($validated);

        return redirect()
            ->route('curriculum-feedback.thankyou')
            ->with('success', 'Thank you! Your feedback has been submitted successfully.');
    }

    /**
     * Display the specified feedback.
     */
    public function show(CurriculumFeedback $curriculumFeedback)
    {
        // Only admins can view individual feedbacks
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');

        $feedback = $curriculumFeedback->load('user');

        return view('admin.curriculum-feedback.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified feedback.
     */
    public function edit(CurriculumFeedback $curriculumFeedback)
    {
        // Only admins can edit feedbacks
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');

        $feedback = $curriculumFeedback;
        $respondentType = $feedback->respondent_type;

        return view('admin.curriculum-feedback.edit', compact('feedback', 'respondentType'));
    }

    /**
     * Update the specified feedback in storage.
     */
    public function update(Request $request, CurriculumFeedback $curriculumFeedback)
    {
        // Only admins can update feedbacks
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');

        $validated = $request->validate([
            'respondent_type' => 'required|in:academician,teacher,industry',
            'institute' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'program' => 'required|string|max:255',
            'course' => 'nullable|string|max:255',
            'content_of_syllabus' => 'required|integer|min:1|max:5',
            'relevance_to_industry' => 'required|integer|min:1|max:5',
            'course_outcomes_defined' => 'required|integer|min:1|max:5',
            'reading_materials_resources' => 'required|integer|min:1|max:5',
            'advanced_topics' => 'required|integer|min:1|max:5',
            'pedagogy_proposed' => 'required|integer|min:1|max:5',
            'theory_practical_balance' => 'required|integer|min:1|max:5',
            'assessment_methods' => 'required|integer|min:1|max:5',
            'project_component' => 'required|integer|min:1|max:5',
            'industrial_training' => 'required|integer|min:1|max:5',
            'additional_suggestions' => 'nullable|string',
            'academic_year' => 'nullable|string|max:50',
            'status' => 'nullable|in:submitted,reviewed',
        ]);

        $curriculumFeedback->update($validated);

        return redirect()
            ->route('curriculum-feedback.show', $curriculumFeedback)
            ->with('success', 'Feedback updated successfully.');
    }

    /**
     * Remove the specified feedback from storage.
     */
    public function destroy(CurriculumFeedback $curriculumFeedback)
    {
        // Only admins can delete feedbacks
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');

        $curriculumFeedback->delete();

        return redirect()
            ->route('curriculum-feedback.index')
            ->with('success', 'Feedback deleted successfully.');
    }

    /**
     * Display analytics/reports for curriculum feedback.
     */
    public function analytics(Request $request)
    {
        // Only admins can view analytics
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');

        $academicYear = $request->get('academic_year');

        $query = CurriculumFeedback::query();
        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        // Get average ratings by respondent type
        $averages = [
            'academic' => $this->calculateAverages($query->clone()->where('respondent_type', 'academic')->get()),
            'teacher' => $this->calculateAverages($query->clone()->where('respondent_type', 'teacher')->get()),
            'industry' => $this->calculateAverages($query->clone()->where('respondent_type', 'industry')->get()),
            'overall' => $this->calculateAverages($query->clone()->get()),
        ];

        // Get response counts by type
        $responseCounts = [
            'academic' => $query->clone()->where('respondent_type', 'academic')->count(),
            'teacher' => $query->clone()->where('respondent_type', 'teacher')->count(),
            'industry' => $query->clone()->where('respondent_type', 'industry')->count(),
        ];

        // Get unique academic years for filter
        $academicYears = CurriculumFeedback::distinct()->pluck('academic_year')->filter();

        return view('admin.curriculum-feedback.analytics', compact('averages', 'responseCounts', 'academicYears', 'academicYear'));
    }

    /**
     * Calculate average ratings for a collection of feedbacks.
     */
    private function calculateAverages($feedbacks)
    {
        if ($feedbacks->isEmpty()) {
            return null;
        }

        $questions = \App\Models\CurriculumFeedback::getQuestions();
        $averages = [];
        
        foreach ($questions as $field => $label) {
            $values = $feedbacks->pluck($field)->filter();
            $averages[$field] = $values->isEmpty() ? 0 : round($values->avg(), 2);
        }

        return $averages;
    }

    /**
     * Thank you page after submission.
     */
    public function thankyou()
    {
        return view('admin.curriculum-feedback.thankyou');
    }

    /**
     * Export feedbacks to Excel.
     */
    public function export(Request $request)
    {
        // Only admins can export
        abort_unless(auth()->user()->hasRole('Admin'), 403, 'Unauthorized action.');

        $query = CurriculumFeedback::query();

        if ($request->filled('respondent_type')) {
            $query->where('respondent_type', $request->respondent_type);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $feedbacks = $query->get();

        // Generate CSV
        $filename = 'curriculum_feedback_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($feedbacks) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'Respondent Type', 'Institute', 'Email', 'Phone', 'Program', 'Course',
                'Content of Syllabus', 'Relevance to Industry', 'Course Outcomes Defined', 
                'Reading Materials Resources', 'Advanced Topics', 'Pedagogy Proposed',
                'Theory Practical Balance', 'Assessment Methods', 'Project Component', 'Industrial Training',
                'Additional Suggestions', 'Academic Year', 'Status', 'Submitted At'
            ]);

            // Add data
            foreach ($feedbacks as $feedback) {
                fputcsv($file, [
                    $feedback->id,
                    $feedback->respondent_type_name,
                    $feedback->institute,
                    $feedback->email,
                    $feedback->phone,
                    $feedback->program,
                    $feedback->course,
                    $feedback->content_of_syllabus,
                    $feedback->relevance_to_industry,
                    $feedback->course_outcomes_defined,
                    $feedback->reading_materials_resources,
                    $feedback->advanced_topics,
                    $feedback->pedagogy_proposed,
                    $feedback->theory_practical_balance,
                    $feedback->assessment_methods,
                    $feedback->project_component,
                    $feedback->industrial_training,
                    $feedback->additional_suggestions,
                    $feedback->academic_year,
                    $feedback->status,
                    $feedback->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
