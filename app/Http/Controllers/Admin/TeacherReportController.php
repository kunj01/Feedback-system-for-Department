<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\FormResponse;
use App\Models\FormAssignment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherReportController extends Controller
{
    public function index()
    {
        // Get all teachers with their feedback counts
        $teachers = Teacher::withCount(['assignments as feedback_count' => function ($query) {
            $query->whereHas('formResponse');
        }])
        ->with('subjects')
        ->orderBy('name')
        ->get();

        return view('admin.teacher-reports.index', compact('teachers'));
    }

    public function show($teacherId)
    {
        $teacher = Teacher::with(['subjects', 'assignments.formResponse', 'assignments.subject'])
            ->findOrFail($teacherId);

        // Get all feedback responses for this teacher
        $responses = FormResponse::whereHas('formAssignment', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
        ->with(['formAssignment.subject', 'student.user'])
        ->get();

        if ($responses->isEmpty()) {
            return redirect()->route('admin.teacher-reports.index')
                ->with('error', 'No feedback responses found for this teacher.');
        }

        // Analyze the responses
        $analysis = $this->analyzeTeacherFeedback($responses, $teacher);

        return view('admin.teacher-reports.show', compact('teacher', 'responses', 'analysis'));
    }

    public function exportPdf($teacherId)
    {
        $teacher = Teacher::with(['subjects', 'assignments.formResponse', 'assignments.subject'])
            ->findOrFail($teacherId);

        $responses = FormResponse::whereHas('formAssignment', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
        ->with(['formAssignment.subject', 'student.user'])
        ->get();

        if ($responses->isEmpty()) {
            return redirect()->route('admin.teacher-reports.index')
                ->with('error', 'No feedback responses found for this teacher.');
        }

        $analysis = $this->analyzeTeacherFeedback($responses, $teacher);

        $pdf = Pdf::loadView('admin.teacher-reports.pdf', compact('teacher', 'responses', 'analysis'));
        return $pdf->download('teacher_report_' . $teacher->name . '_' . now()->format('Y-m-d') . '.pdf');
    }

    private function analyzeTeacherFeedback($responses, $teacher)
    {
        $totalResponses = $responses->count();

        // Define question mapping for Student Feedback Form
        $questionCategories = [
            'instructor_experience' => [
                'instructor_approachable' => 'The instructor is approachable/Instructor makes himself/herself available to students in and out of the class.',
                'instructor_effective' => 'Instructor was an effective lecturer/demonstrates knowledge and expertise in the subject matter.',
                'presentations_clear' => 'Presentations of the instructor were clear and organized.',
                'instructor_stimulated' => 'Instructor stimulated student interest/The instructor uses a variety of teaching methods.',
                'instructor_used_time' => 'Instructor effectively used time during class.',
                'instructor_introduces_concepts' => 'The way the instructor introduces new concepts was clear.',
                'instructor_positive_environment' => 'The instructor creates a positive environment in class.',
                'instructor_communicates' => 'The instructor clearly communicate course expectations/requirements and policies.'
            ]
        ];

        $ratings = ['Strongly Agree', 'Agree', 'Neutral', 'Disagree', 'Strongly Disagree'];
        $ratingMap = ['Strongly Agree' => 5, 'Agree' => 4, 'Neutral' => 3, 'Disagree' => 2, 'Strongly Disagree' => 1];

        $questionStats = [];
        $overallStats = [];
        $strengthsAndWeaknesses = [];

        // Analyze instructor-related questions
        foreach ($questionCategories['instructor_experience'] as $field => $label) {
            $questionData = [
                'question' => $label,
                'responses' => array_fill_keys($ratings, 0),
                'total' => 0,
                'average' => 0,
                'reasoning' => []
            ];

            foreach ($responses as $response) {
                $responseData = $response->responses;
                
                if (isset($responseData[$field]['rating'])) {
                    $rating = $responseData[$field]['rating'];
                    if (isset($questionData['responses'][$rating])) {
                        $questionData['responses'][$rating]++;
                        $questionData['total']++;
                    }

                    // Collect reasoning for low ratings
                    if (in_array($rating, ['Disagree', 'Strongly Disagree']) && 
                        isset($responseData[$field]['reasoning']) && 
                        !empty($responseData[$field]['reasoning'])) {
                        $questionData['reasoning'][] = $responseData[$field]['reasoning'];
                    }
                }
            }

            // Calculate average and percentages
            if ($questionData['total'] > 0) {
                $weightedSum = 0;
                foreach ($questionData['responses'] as $rating => $count) {
                    $questionData['percentages'][$rating] = round(($count / $questionData['total']) * 100, 1);
                    $weightedSum += $ratingMap[$rating] * $count;
                }
                $questionData['average'] = round($weightedSum / $questionData['total'], 2);
            }

            $questionStats[$field] = $questionData;

            // Identify strengths and weaknesses
            if ($questionData['average'] >= 4.5) {
                $strengthsAndWeaknesses['strengths'][] = [
                    'question' => $label,
                    'average' => $questionData['average']
                ];
            } elseif ($questionData['average'] < 3.0) {
                $strengthsAndWeaknesses['weaknesses'][] = [
                    'question' => $label,
                    'average' => $questionData['average'],
                    'reasoning' => $questionData['reasoning']
                ];
            }
        }

        // Calculate overall statistics
        $allAverages = array_column($questionStats, 'average');
        $overallAverage = count($allAverages) > 0 ? round(array_sum($allAverages) / count($allAverages), 2) : 0;

        // Calculate rating distribution across all questions
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

        // Collect low rating reasoning
        $lowRatingReasoning = [];
        foreach ($questionStats as $field => $stats) {
            if (!empty($stats['reasoning'])) {
                $lowRatingReasoning[$field] = $stats['reasoning'];
            }
        }

        // Generate descriptive analysis
        $descriptiveAnalysis = $this->generateDescriptiveAnalysis($teacher, $totalResponses, $overallAverage, $overallPercentages, $strengthsAndWeaknesses);

        // Generate recommendations
        $recommendations = $this->generateRecommendations($overallAverage, $strengthsAndWeaknesses);

        return [
            'total_responses' => $totalResponses,
            'question_stats' => $questionStats,
            'overall_average' => $overallAverage,
            'overall_distribution' => $overallDistribution,
            'overall_percentages' => $overallPercentages,
            'rating_distribution' => $overallPercentages,
            'strengths_weaknesses' => $strengthsAndWeaknesses,
            'descriptive_analysis' => $descriptiveAnalysis,
            'recommendations' => $recommendations,
            'low_rating_reasoning' => $lowRatingReasoning,
            'title_info' => [
                'institute' => 'U. V. Patel College of Engineering',
                'teacher_name' => $teacher->name,
                'department' => $teacher->department ?? 'N/A',
                'subjects' => $teacher->subjects->pluck('name')->join(', '),
                'academic_year' => '2023-24',
                'report_date' => now()->format('F d, Y'),
                'total_responses' => $totalResponses
            ]
        ];
    }

    private function generateDescriptiveAnalysis($teacher, $totalResponses, $overallAverage, $percentages, $strengthsWeaknesses)
    {
        $analysis = [];

        $analysis[] = "This report presents a comprehensive analysis of student feedback for **{$teacher->name}** based on {$totalResponses} responses.";
        
        $analysis[] = "The overall average rating across all parameters is **{$overallAverage}/5.0**, indicating a " . 
                     ($overallAverage >= 4.0 ? "strong positive" : ($overallAverage >= 3.0 ? "satisfactory" : "needs improvement")) . 
                     " performance level.";

        $stronglyAgree = $percentages['Strongly Agree'] ?? 0;
        $agree = $percentages['Agree'] ?? 0;
        $positive = $stronglyAgree + $agree;
        
        $analysis[] = "**{$positive}%** of all responses were positive (Strongly Agree + Agree), with **{$stronglyAgree}%** being Strongly Agree.";

        if (isset($strengthsWeaknesses['strengths']) && count($strengthsWeaknesses['strengths']) > 0) {
            $count = count($strengthsWeaknesses['strengths']);
            $analysis[] = "The instructor demonstrated exceptional performance in **{$count} area(s)**, with ratings above 4.5/5.0.";
        }

        if (isset($strengthsWeaknesses['weaknesses']) && count($strengthsWeaknesses['weaknesses']) > 0) {
            $count = count($strengthsWeaknesses['weaknesses']);
            $analysis[] = "There are **{$count} area(s)** requiring attention, with ratings below 3.0/5.0, indicating opportunities for improvement.";
        }

        return $analysis;
    }

    private function generateRecommendations($overallAverage, $strengthsWeaknesses)
    {
        $recommendations = [];

        if ($overallAverage >= 4.5) {
            $recommendations[] = "Continue implementing current teaching methodologies and strategies, as they are highly effective.";
            $recommendations[] = "Consider mentoring other faculty members to share best practices and successful teaching approaches.";
            $recommendations[] = "Maintain regular engagement with students to sustain the high level of satisfaction.";
        } elseif ($overallAverage >= 4.0) {
            $recommendations[] = "Maintain current high standards while exploring opportunities for minor enhancements.";
            if (isset($strengthsWeaknesses['weaknesses'])) {
                $recommendations[] = "Focus on addressing the identified areas of improvement to achieve excellence across all parameters.";
            }
        } elseif ($overallAverage >= 3.0) {
            $recommendations[] = "Review and enhance teaching methodologies to improve overall student satisfaction.";
            $recommendations[] = "Seek feedback from students through informal discussions to better understand their learning needs.";
            if (isset($strengthsWeaknesses['weaknesses'])) {
                $recommendations[] = "Prioritize improvement in low-rated areas through targeted professional development.";
            }
        } else {
            $recommendations[] = "Immediate intervention recommended to address significant student concerns.";
            $recommendations[] = "Participate in faculty development programs focusing on teaching excellence and student engagement.";
            $recommendations[] = "Implement structured mentoring with experienced faculty to enhance teaching effectiveness.";
            $recommendations[] = "Conduct mid-semester feedback sessions to identify and address issues proactively.";
        }

        // Add specific recommendations based on weaknesses
        if (isset($strengthsWeaknesses['weaknesses'])) {
            foreach ($strengthsWeaknesses['weaknesses'] as $weakness) {
                if (stripos($weakness['question'], 'approachable') !== false) {
                    $recommendations[] = "Increase availability for student consultations through regular office hours and online availability.";
                } elseif (stripos($weakness['question'], 'clear') !== false || stripos($weakness['question'], 'organized') !== false) {
                    $recommendations[] = "Enhance lecture clarity through structured presentations, visual aids, and organized course materials.";
                } elseif (stripos($weakness['question'], 'interest') !== false || stripos($weakness['question'], 'methods') !== false) {
                    $recommendations[] = "Incorporate diverse teaching methods including interactive sessions, case studies, and practical applications.";
                } elseif (stripos($weakness['question'], 'time') !== false) {
                    $recommendations[] = "Improve time management in class to ensure complete coverage of topics within allotted time.";
                }
            }
        }

        return $recommendations;
    }
}
