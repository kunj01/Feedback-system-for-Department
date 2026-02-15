<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\FormResponse;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentFeedbackController extends Controller
{
    /**
     * Display all student feedback responses
     */
    public function index(Request $request)
    {
        try {
            Log::info('Admin viewing feedback index', [
                'user_id' => auth()->id(),
                'filters' => $request->all()
            ]);

            // Fetch from form_responses table instead of feedback
            $query = FormResponse::with(['student.user', 'formAssignment.teacher', 'formAssignment.subject'])
                ->latest();

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('student.user', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('formAssignment.teacher', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('formAssignment.subject', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                });
            }

            // Filter by subject
            if ($request->filled('subject_id')) {
                $query->whereHas('formAssignment', function($q) use ($request) {
                    $q->where('subject_id', $request->subject_id);
                });
            }

            // Filter by faculty/teacher
            if ($request->filled('faculty_id')) {
                $query->whereHas('formAssignment', function($q) use ($request) {
                    $q->where('teacher_id', $request->faculty_id);
                });
            }

            // Filter by rating (check if responses contain ratings)
            if ($request->filled('rating')) {
                // This is tricky with JSON, we'll skip for now or implement custom logic
            }

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $feedbacks = $query->paginate(20)->withQueryString();

            Log::info('Form responses fetched', [
                'count' => $feedbacks->total()
            ]);

            // Get statistics from form_responses
            $stats = [
                'total' => FormResponse::count(),
                'average_rating' => $this->calculateAverageRating(),
                'this_month' => FormResponse::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'ratings_breakdown' => [
                    5 => $this->countResponsesByRating(5),
                    4 => $this->countResponsesByRating(4),
                    3 => $this->countResponsesByRating(3),
                    2 => $this->countResponsesByRating(2),
                    1 => $this->countResponsesByRating(1),
                ],
            ];

            // Get unique subjects and teachers for filters
            $subjects = \App\Models\Subject::orderBy('name')->get();
            $faculties = \App\Models\Teacher::orderBy('name')->get();

            return view('admin.student-feedback.index', compact('feedbacks', 'stats', 'subjects', 'faculties'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching feedback list: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error loading feedback: ' . $e->getMessage());
        }
    }

    /**
     * Calculate average rating from form responses
     */
    private function calculateAverageRating()
    {
        $responses = FormResponse::all();
        if ($responses->isEmpty()) {
            return '0.0';
        }

        $totalRatings = 0;
        $ratingCount = 0;

        foreach ($responses as $response) {
            $responseData = $response->responses;
            if (is_array($responseData)) {
                foreach ($responseData as $key => $value) {
                    if (is_numeric($value) && $value >= 1 && $value <= 5) {
                        $totalRatings += $value;
                        $ratingCount++;
                    }
                }
            }
        }

        return $ratingCount > 0 ? number_format($totalRatings / $ratingCount, 1) : '0.0';
    }

    /**
     * Count responses by rating
     */
    private function countResponsesByRating($rating)
    {
        $count = 0;
        $responses = FormResponse::all();

        foreach ($responses as $response) {
            $responseData = $response->responses;
            if (is_array($responseData)) {
                foreach ($responseData as $key => $value) {
                    if ($value == $rating) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Display the specified feedback
     */
    public function show($id)
    {
        $feedback = Feedback::with(['student.user'])->findOrFail($id);
        
        return view('admin.student-feedback.show', compact('feedback'));
    }

    /**
     * Export feedback data
     */
    public function export(Request $request)
    {
        $feedbacks = Feedback::with(['student.user'])
            ->when($request->filled('subject_id'), function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            })
            ->when($request->filled('faculty_id'), function($q) use ($request) {
                $q->where('faculty_id', $request->faculty_id);
            })
            ->get();

        $filename = 'student-feedback-' . now()->format('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($feedbacks) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID',
                'Student Name',
                'Subject ID',
                'Faculty ID',
                'Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6', 'Q7', 'Q8',
                'Overall Rating',
                'Comments',
                'Submitted At'
            ]);

            // Data
            foreach ($feedbacks as $feedback) {
                $responses = $feedback->responses;
                fputcsv($file, [
                    $feedback->id,
                    $feedback->student->user->name ?? 'N/A',
                    $feedback->subject_id,
                    $feedback->faculty_id,
                    $responses['q1'] ?? '',
                    $responses['q2'] ?? '',
                    $responses['q3'] ?? '',
                    $responses['q4'] ?? '',
                    $responses['q5'] ?? '',
                    $responses['q6'] ?? '',
                    $responses['q7'] ?? '',
                    $responses['q8'] ?? '',
                    $feedback->overall_rating,
                    $feedback->comments ?? '',
                    $feedback->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get analytics data
     */
    public function analytics()
    {
        // Rating distribution
        $ratingDistribution = Feedback::select('overall_rating', DB::raw('count(*) as count'))
            ->groupBy('overall_rating')
            ->orderBy('overall_rating')
            ->get();

        // Feedback by subject
        $feedbackBySubject = Feedback::select('subject_id', DB::raw('count(*) as count'))
            ->groupBy('subject_id')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // Feedback by faculty
        $feedbackByFaculty = Feedback::select('faculty_id', DB::raw('count(*) as count'))
            ->groupBy('faculty_id')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // Feedback trends (last 7 days)
        $feedbackTrends = Feedback::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.student-feedback.analytics', compact(
            'ratingDistribution',
            'feedbackBySubject',
            'feedbackByFaculty',
            'feedbackTrends'
        ));
    }

    /**
     * Delete feedback (optional - if needed)
     */
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('admin.student-feedback.index')
            ->with('success', 'Feedback deleted successfully!');
    }

    /**
     * Generate comprehensive analysis report for Student Feedback
     */
    public function analysisReport()
    {
        // Get all Student Feedback form responses
        $responses = FormResponse::with(['student.user', 'formAssignment.teacher', 'formAssignment.subject'])
            ->get();

        if ($responses->isEmpty()) {
            return redirect()->route('admin.student-feedback.index')
                ->with('error', 'No student feedback responses found to generate analysis.');
        }

        // Analyze the data
        $analysis = $this->analyzeStudentFeedback($responses);

        return view('admin.student-feedback.analysis-report', compact('responses', 'analysis'));
    }

    /**
     * Export analysis report as PDF
     */
    public function exportAnalysisPdf()
    {
        $responses = FormResponse::with(['student.user', 'formAssignment.teacher', 'formAssignment.subject'])
            ->get();

        if ($responses->isEmpty()) {
            return redirect()->route('admin.student-feedback.index')
                ->with('error', 'No student feedback responses found to generate PDF.');
        }

        $analysis = $this->analyzeStudentFeedback($responses);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.student-feedback.analysis-report-pdf', compact('responses', 'analysis'));
        return $pdf->download('student_feedback_analysis_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Analyze student feedback data
     */
    private function analyzeStudentFeedback($responses)
    {
        $totalResponses = $responses->count();

        // Define all questions from Student Feedback Form
        $allQuestions = [
            // Section 1: Student Experience
            'prepare_for_class' => 'I prepare for class lectures.',
            'ask_questions_freely' => 'I am able to ask questions freely during class.',
            'actively_participate' => 'I actively participate in class.',
            'feel_comfortable_sharing' => 'I feel comfortable sharing my ideas in this course.',
            'developing_skills' => 'I am developing the skills I need in this class.',
            
            // Section 2: Instructor Experience
            'instructor_approachable' => 'The instructor is approachable/Instructor makes himself/herself available to students in and out of the class.',
            'instructor_effective' => 'Instructor was an effective lecturer/demonstrates knowledge and expertise in the subject matter.',
            'presentations_clear' => 'Presentations of the instructor were clear and organized.',
            'instructor_stimulated' => 'Instructor stimulated student interest/The instructor uses a variety of teaching methods.',
            'instructor_used_time' => 'Instructor effectively used time during class.',
            'instructor_introduces_concepts' => 'The way the instructor introduces new concepts was clear.',
            'instructor_positive_environment' => 'The instructor creates a positive environment in class.',
            'instructor_communicates' => 'The instructor clearly communicate course expectations/requirements and policies.',
            
            // Section 3: Course Content
            'learning_objectives_clear' => 'Learning objectives were clear.',
            'content_organized' => 'Course content was organized and well presented.',
            'opportunities_practice' => 'There are sufficient opportunities to practice.',
            'access_materials' => 'Able to access all course materials.',
            'content_prepares' => 'Course content prepares you for further studies or your career.',
            'teaching_assessments' => 'Teaching methods and assessments in relation to the learning objectives and outcomes.',
            'diverse_perspectives' => 'The course included diverse perspectives.'
        ];

        $ratings = ['Strongly Agree', 'Agree', 'Neutral', 'Disagree', 'Strongly Disagree'];
        $ratingMap = ['Strongly Agree' => 5, 'Agree' => 4, 'Neutral' => 3, 'Disagree' => 2, 'Strongly Disagree' => 1];

        $questionStats = [];
        
        // Analyze each question
        foreach ($allQuestions as $field => $label) {
            $questionData = [
                'question' => $label,
                'responses' => array_fill_keys($ratings, 0),
                'total' => 0,
                'average' => 0
            ];

            foreach ($responses as $response) {
                $responseData = $response->responses;
                
                if (isset($responseData[$field]['rating'])) {
                    $rating = $responseData[$field]['rating'];
                    if (isset($questionData['responses'][$rating])) {
                        $questionData['responses'][$rating]++;
                        $questionData['total']++;
                    }
                }
            }

            // Calculate percentages and average
            if ($questionData['total'] > 0) {
                $weightedSum = 0;
                foreach ($questionData['responses'] as $rating => $count) {
                    $questionData['percentages'][$rating] = round(($count / $questionData['total']) * 100, 1);
                    $weightedSum += $ratingMap[$rating] * $count;
                }
                $questionData['average'] = round($weightedSum / $questionData['total'], 2);
            }

            $questionStats[$field] = $questionData;
        }

        // Calculate overall statistics
        $allAverages = array_column($questionStats, 'average');
        $overallAverage = count($allAverages) > 0 ? round(array_sum($allAverages) / count($allAverages), 2) : 0;

        // Calculate overall rating distribution
        $overallDistribution = array_fill_keys($ratings, 0);
        foreach ($questionStats as $stats) {
            foreach ($ratings as $rating) {
                $overallDistribution[$rating] += $stats['responses'][$rating];
            }
        }

        $totalAllResponses = array_sum($overallDistribution);
        $overallPercentages = [];
        foreach ($overallDistribution as $rating => $count) {
            $overallPercentages[$rating] = $totalAllResponses > 0 ? round(($count / $totalAllResponses) * 100, 1) : 0;
        }

        // Generate descriptive analysis
        $descriptiveAnalysis = $this->generateOverallDescriptiveAnalysis($totalResponses, $overallAverage, $overallPercentages, $questionStats);

        // Identify strengths and areas for improvement
        $strengthsAndWeaknesses = $this->identifyStrengthsWeaknesses($questionStats);

        // Generate recommendations
        $recommendations = $this->generateOverallRecommendations($overallAverage, $strengthsAndWeaknesses);

        return [
            'total_responses' => $totalResponses,
            'question_stats' => $questionStats,
            'overall_average' => $overallAverage,
            'overall_distribution' => $overallDistribution,
            'overall_percentages' => $overallPercentages,
            'descriptive_analysis' => $descriptiveAnalysis,
            'strengths_weaknesses' => $strengthsAndWeaknesses,
            'recommendations' => $recommendations,
            'title_info' => [
                'institute' => 'U. V. Patel College of Engineering',
                'academic_year' => '2023-24',
                'report_date' => now()->format('F d, Y'),
                'total_responses' => $totalResponses
            ]
        ];
    }

    private function generateOverallDescriptiveAnalysis($totalResponses, $overallAverage, $percentages, $questionStats)
    {
        $analysis = [];

        $analysis[] = "This comprehensive analysis is based on {$totalResponses} student feedback responses collected through the Student Feedback System.";
        
        $analysis[] = "The overall average rating across all parameters is **{$overallAverage}/5.0**, indicating a " . 
                     ($overallAverage >= 4.0 ? "strong positive" : ($overallAverage >= 3.0 ? "satisfactory" : "needs improvement")) . 
                     " level of student satisfaction.";

        $stronglyAgree = $percentages['Strongly Agree'] ?? 0;
        $agree = $percentages['Agree'] ?? 0;
        $positive = $stronglyAgree + $agree;
        
        $analysis[] = "**{$positive}%** of all responses were positive (Strongly Agree + Agree), with **{$stronglyAgree}%** expressing strong agreement.";

        // Analyze specific sections
        $instructorQuestions = array_filter($questionStats, function($key) {
            return strpos($key, 'instructor_') === 0;
        }, ARRAY_FILTER_USE_KEY);
        
        if (!empty($instructorQuestions)) {
            $instructorAvg = round(array_sum(array_column($instructorQuestions, 'average')) / count($instructorQuestions), 2);
            $analysis[] = "The instructor-related parameters received an average rating of **{$instructorAvg}/5.0**, reflecting student perception of teaching effectiveness.";
        }

        $analysis[] = "Student engagement and participation indicators show " . 
                     ($overallAverage >= 4.0 ? "strong" : "moderate") . 
                     " levels of active learning and classroom interaction.";

        return $analysis;
    }

    private function identifyStrengthsWeaknesses($questionStats)
    {
        $strengths = [];
        $weaknesses = [];

        foreach ($questionStats as $field => $stats) {
            if ($stats['average'] >= 4.5) {
                $strengths[] = [
                    'question' => $stats['question'],
                    'average' => $stats['average'],
                    'field' => $field
                ];
            } elseif ($stats['average'] < 3.0) {
                $weaknesses[] = [
                    'question' => $stats['question'],
                    'average' => $stats['average'],
                    'field' => $field
                ];
            }
        }

        // Sort by average
        usort($strengths, function($a, $b) {
            return $b['average'] <=> $a['average'];
        });
        
        usort($weaknesses, function($a, $b) {
            return $a['average'] <=> $b['average'];
        });

        return [
            'strengths' => $strengths,
            'weaknesses' => $weaknesses
        ];
    }

    private function generateOverallRecommendations($overallAverage, $strengthsWeaknesses)
    {
        $recommendations = [];

        $recommendations[] = "Continue fostering a positive learning environment that encourages student participation and engagement.";
        
        if (!empty($strengthsWeaknesses['strengths'])) {
            $recommendations[] = "Recognize and share best practices from high-performing areas across all courses and instructors.";
        }

        if (!empty($strengthsWeaknesses['weaknesses'])) {
            $recommendations[] = "Develop targeted improvement plans for identified areas of concern, focusing on enhancing teaching methodologies and course content.";
            $recommendations[] = "Conduct faculty development workshops addressing specific improvement areas highlighted in the feedback.";
        }

        $recommendations[] = "Implement regular mid-semester feedback mechanisms to address student concerns proactively.";
        $recommendations[] = "Enhance communication channels between students and instructors to foster better understanding of course expectations.";
        $recommendations[] = "Review and update course materials regularly to ensure relevance and accessibility for all students.";
        $recommendations[] = "Encourage peer learning and collaborative teaching approaches to improve overall educational experience.";

        return $recommendations;
    }

    /**
     * All question labels mapped by field name
     */
    private function getQuestionLabels()
    {
        return [
            // Section 1: Student experience
            'prepare_for_class' => 'I prepare for class lectures.',
            'ask_questions_freely' => 'I am able to ask questions freely during class.',
            'actively_participate' => 'I actively participate in class.',
            'feel_comfortable_sharing' => 'I feel comfortable sharing my ideas in this course.',
            'developing_skills' => 'I am developing the skills I need in this class.',
            // Section 2: Instructor experience
            'instructor_approachable' => 'The instructor is approachable / available to students.',
            'instructor_effective' => 'Instructor was an effective lecturer / demonstrates knowledge.',
            'presentations_clear' => 'Presentations were clear and organized.',
            'instructor_stimulated' => 'Instructor stimulated student interest / uses variety of methods.',
            'instructor_used_time' => 'Instructor effectively used time during class.',
            'instructor_introduces_concepts' => 'The way the instructor introduces new concepts was clear.',
            'instructor_positive_environment' => 'The instructor creates a positive environment in class.',
            'instructor_communicates' => 'The instructor clearly communicates course expectations.',
            // Section 3: Course content
            'learning_objectives_clear' => 'Learning objectives were clear.',
            'content_organized' => 'Course content was organized and well presented.',
            'opportunities_practice' => 'There are sufficient opportunities to practice.',
            'access_materials' => 'Able to access all course materials.',
            'content_prepares' => 'Course content prepares you for further studies or career.',
            'teaching_assessments' => 'Teaching methods and assessments relate to learning objectives.',
            'diverse_perspectives' => 'The course included diverse perspectives.',
        ];
    }

    /**
     * Display all feedback responses that contain Disagree or Strongly Disagree ratings
     */
    public function disagreeResponses(Request $request)
    {
        try {
            $questionLabels = $this->getQuestionLabels();
            $disagreeItems = collect();

            $query = FormResponse::with(['student.user', 'formAssignment.teacher', 'formAssignment.subject'])
                ->latest();

            // Apply filters
            if ($request->filled('subject_id')) {
                $query->whereHas('formAssignment', function ($q) use ($request) {
                    $q->where('subject_id', $request->subject_id);
                });
            }
            if ($request->filled('teacher_id')) {
                $query->whereHas('formAssignment', function ($q) use ($request) {
                    $q->where('teacher_id', $request->teacher_id);
                });
            }

            $responses = $query->get();

            foreach ($responses as $response) {
                $data = $response->responses;
                if (!is_array($data)) continue;

                foreach ($data as $field => $value) {
                    if (!is_array($value) || !isset($value['rating'])) continue;

                    $rating = $value['rating'];
                    if (in_array($rating, ['Disagree', 'Strongly Disagree'])) {
                        $disagreeItems->push([
                            'response_id' => $response->id,
                            'student_name' => optional(optional($response->student)->user)->name ?? $response->name ?? 'N/A',
                            'student_email' => $response->email ?? optional(optional($response->student)->user)->email ?? 'N/A',
                            'subject' => optional(optional($response->formAssignment)->subject)->name ?? 'N/A',
                            'teacher' => optional(optional($response->formAssignment)->teacher)->name ?? 'N/A',
                            'question' => $questionLabels[$field] ?? $field,
                            'field' => $field,
                            'rating' => $rating,
                            'reasoning' => $value['reasoning'] ?? null,
                            'submitted_at' => $response->created_at,
                        ]);
                    }
                }
            }

            // Sort: Strongly Disagree first, then Disagree
            $disagreeItems = $disagreeItems->sortBy(function ($item) {
                return $item['rating'] === 'Strongly Disagree' ? 0 : 1;
            })->values();

            // Filter by rating type after collecting
            if ($request->filled('rating_type') && $request->rating_type !== 'all') {
                $disagreeItems = $disagreeItems->where('rating', $request->rating_type)->values();
            }

            // Stats
            $stats = [
                'total' => $disagreeItems->count(),
                'strongly_disagree' => $disagreeItems->where('rating', 'Strongly Disagree')->count(),
                'disagree' => $disagreeItems->where('rating', 'Disagree')->count(),
                'with_reasoning' => $disagreeItems->whereNotNull('reasoning')->count(),
            ];

            $subjects = \App\Models\Subject::orderBy('name')->get();
            $teachers = \App\Models\Teacher::orderBy('name')->get();

            return view('admin.student-feedback.disagree', compact('disagreeItems', 'stats', 'subjects', 'teachers'));

        } catch (\Exception $e) {
            Log::error('Error loading disagree responses: ' . $e->getMessage());
            return back()->with('error', 'Error loading disagree responses: ' . $e->getMessage());
        }
    }

    /**
     * Display all comments (open-ended feedback + reasoning texts)
     */
    public function allComments(Request $request)
    {
        try {
            $questionLabels = $this->getQuestionLabels();
            $comments = collect();

            $openEndedFields = [
                'most_useful' => 'What aspects of this course were most useful or valuable?',
                'missing_topics' => 'Were there any topics you felt were missing or needed more emphasis?',
                'improvement_suggestions' => 'Give your suggestion to improve this course',
            ];

            $query = FormResponse::with(['student.user', 'formAssignment.teacher', 'formAssignment.subject'])
                ->latest();

            // Apply filters
            if ($request->filled('subject_id')) {
                $query->whereHas('formAssignment', function ($q) use ($request) {
                    $q->where('subject_id', $request->subject_id);
                });
            }
            if ($request->filled('teacher_id')) {
                $query->whereHas('formAssignment', function ($q) use ($request) {
                    $q->where('teacher_id', $request->teacher_id);
                });
            }

            $responses = $query->get();

            foreach ($responses as $response) {
                $data = $response->responses;
                if (!is_array($data)) continue;

                $studentName = optional(optional($response->student)->user)->name ?? $response->name ?? 'N/A';
                $studentEmail = $response->email ?? optional(optional($response->student)->user)->email ?? 'N/A';
                $subjectName = optional(optional($response->formAssignment)->subject)->name ?? 'N/A';
                $teacherName = optional(optional($response->formAssignment)->teacher)->name ?? 'N/A';

                // Open-ended text answers
                foreach ($openEndedFields as $field => $label) {
                    if (isset($data[$field]) && is_string($data[$field]) && strtolower(trim($data[$field])) !== 'na' && trim($data[$field]) !== '') {
                        $comments->push([
                            'response_id' => $response->id,
                            'student_name' => $studentName,
                            'student_email' => $studentEmail,
                            'subject' => $subjectName,
                            'teacher' => $teacherName,
                            'type' => 'open_ended',
                            'question' => $label,
                            'comment' => $data[$field],
                            'rating' => null,
                            'submitted_at' => $response->created_at,
                        ]);
                    }
                }

                // Disagree reasoning texts
                foreach ($data as $field => $value) {
                    if (is_array($value) && isset($value['reasoning']) && trim($value['reasoning']) !== '') {
                        $comments->push([
                            'response_id' => $response->id,
                            'student_name' => $studentName,
                            'student_email' => $studentEmail,
                            'subject' => $subjectName,
                            'teacher' => $teacherName,
                            'type' => 'reasoning',
                            'question' => $questionLabels[$field] ?? $field,
                            'comment' => $value['reasoning'],
                            'rating' => $value['rating'] ?? null,
                            'submitted_at' => $response->created_at,
                        ]);
                    }
                }
            }

            // Filter by type
            if ($request->filled('type') && $request->type !== 'all') {
                $comments = $comments->where('type', $request->type)->values();
            }

            $stats = [
                'total' => $comments->count(),
                'open_ended' => $comments->where('type', 'open_ended')->count(),
                'reasoning' => $comments->where('type', 'reasoning')->count(),
            ];

            $subjects = \App\Models\Subject::orderBy('name')->get();
            $teachers = \App\Models\Teacher::orderBy('name')->get();

            return view('admin.student-feedback.comments', compact('comments', 'stats', 'subjects', 'teachers'));

        } catch (\Exception $e) {
            Log::error('Error loading comments: ' . $e->getMessage());
            return back()->with('error', 'Error loading comments: ' . $e->getMessage());
        }
    }
}
