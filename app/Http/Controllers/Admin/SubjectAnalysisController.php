<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\FormResponse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SubjectAnalysisController extends Controller
{
    /**
     * Display list of subjects for analysis selection
     */
    public function index()
    {
        // Get all subjects with response counts
        $subjects = Subject::withCount(['formAssignments as responses_count' => function($query) {
            $query->whereHas('formResponse');
        }])
        ->with('teachers')
        ->orderBy('semester')
        ->orderBy('sort_order')
        ->get();

        // Group subjects by semester
        $subjectsBySemester = $subjects->groupBy('semester');

        return view('admin.subject-analysis.index', compact('subjectsBySemester'));
    }

    /**
     * Generate comprehensive analysis report for a specific subject
     */
    public function show($id)
    {
        $subject = Subject::with('teachers')->findOrFail($id);

        // Get all responses for this subject
        $responses = FormResponse::with(['student.user', 'formAssignment.teacher'])
            ->whereHas('formAssignment', function($query) use ($id) {
                $query->where('subject_id', $id);
            })
            ->get();

        if ($responses->isEmpty()) {
            return redirect()->route('admin.subject-analysis.index')
                ->with('error', 'No feedback responses found for this subject.');
        }

        // Analyze the data
        $analysis = $this->analyzeSubjectFeedback($responses, $subject);

        return view('admin.subject-analysis.show', compact('subject', 'responses', 'analysis'));
    }

    /**
     * Export subject analysis report as PDF
     */
    public function exportPdf($id)
    {
        $subject = Subject::with('teachers')->findOrFail($id);

        $responses = FormResponse::with(['student.user', 'formAssignment.teacher'])
            ->whereHas('formAssignment', function($query) use ($id) {
                $query->where('subject_id', $id);
            })
            ->get();

        if ($responses->isEmpty()) {
            return redirect()->route('admin.subject-analysis.index')
                ->with('error', 'No feedback responses found to generate PDF.');
        }

        $analysis = $this->analyzeSubjectFeedback($responses, $subject);

        $pdf = Pdf::loadView('admin.subject-analysis.pdf', compact('subject', 'responses', 'analysis'));
        return $pdf->download('subject_analysis_' . $subject->code . '_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Analyze subject feedback data
     */
    private function analyzeSubjectFeedback($responses, $subject)
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
            'instructor_used_time' => 'Instructor used class time effectively.',
            'instructor_introduces_concepts' => 'Instructor introduces new concepts with examples/Instructor clearly explains difficult concepts.',
            'instructor_positive_environment' => 'Instructor creates a positive learning environment.',
            'instructor_communicates' => 'Instructor communicates clearly in class.',
            
            // Section 3: Course Structure  
            'learning_objectives_clear' => 'The learning objectives were clearly communicated.',
            'content_organized' => 'The course content was well-organized and logically structured.',
            'opportunities_practice' => 'There were sufficient opportunities to practice and apply what I learned.',
            'access_materials' => 'I had adequate access to course materials and resources.',
            'content_prepares' => 'The course content prepares me well for future courses or career.',
            'teaching_assessments' => 'The teaching methods and assessments were appropriate for this course.',
            'diverse_perspectives' => 'The course encouraged diverse perspectives and critical thinking.',
        ];

        // Calculate statistics for each question
        $questionStats = [];
        $totalRatings = 0;
        $ratingCount = 0;

        foreach ($allQuestions as $field => $question) {
            $ratings = [
                'Strongly Agree' => 0,
                'Agree' => 0,
                'Neutral' => 0,
                'Disagree' => 0,
                'Strongly Disagree' => 0,
            ];

            $sum = 0;
            $count = 0;

            foreach ($responses as $response) {
                if (isset($response->responses[$field])) {
                    $responseData = $response->responses[$field];
                    
                    // Handle both formats: direct rating or object with 'rating' key
                    if (is_array($responseData) && isset($responseData['rating'])) {
                        $ratingText = $responseData['rating'];
                    } elseif (is_string($responseData)) {
                        $ratingText = $responseData;
                    } else {
                        continue;
                    }
                    
                    // Convert text rating to numeric value
                    $value = $this->convertRatingToNumeric($ratingText);
                    
                    if ($value > 0) {
                        $sum += $value;
                        $count++;
                        
                        switch ($value) {
                            case 5: $ratings['Strongly Agree']++; break;
                            case 4: $ratings['Agree']++; break;
                            case 3: $ratings['Neutral']++; break;
                            case 2: $ratings['Disagree']++; break;
                            case 1: $ratings['Strongly Disagree']++; break;
                        }
                    }
                }
            }

            if ($count > 0) {
                $average = round($sum / $count, 2);
                $totalRatings += $sum;
                $ratingCount += $count;

                $percentages = [];
                foreach ($ratings as $rating => $ratingCountValue) {
                    $percentages[$rating] = $count > 0 ? round(($ratingCountValue / $count) * 100, 1) : 0;
                }

                $questionStats[$field] = [
                    'question' => $question,
                    'responses' => $ratings,
                    'percentages' => $percentages,
                    'average' => $average,
                    'count' => $count,
                ];
            }
        }

        // Calculate overall metrics
        $overallAverage = $ratingCount > 0 ? round($totalRatings / $ratingCount, 2) : 0;

        // Calculate overall distribution
        $overallDistribution = [
            'Strongly Agree' => 0,
            'Agree' => 0,
            'Neutral' => 0,
            'Disagree' => 0,
            'Strongly Disagree' => 0,
        ];

        foreach ($questionStats as $stats) {
            foreach ($stats['responses'] as $rating => $count) {
                $overallDistribution[$rating] += $count;
            }
        }

        $totalOverallResponses = array_sum($overallDistribution);
        $overallPercentages = [];
        foreach ($overallDistribution as $rating => $count) {
            $overallPercentages[$rating] = $totalOverallResponses > 0 ? round(($count / $totalOverallResponses) * 100, 1) : 0;
        }

        // Identify strengths and weaknesses
        $strengthsWeaknesses = $this->identifyStrengthsWeaknesses($questionStats);

        // Generate descriptive analysis
        $descriptiveAnalysis = $this->generateDescriptiveAnalysis($subject, $totalResponses, $overallAverage, $overallPercentages, $questionStats);

        // Generate recommendations
        $recommendations = $this->generateRecommendations($overallAverage, $strengthsWeaknesses);

        // Get teacher-wise breakdown
        $teacherBreakdown = $this->getTeacherBreakdown($responses);

        return [
            'total_responses' => $totalResponses,
            'question_stats' => $questionStats,
            'overall_average' => $overallAverage,
            'overall_distribution' => $overallDistribution,
            'overall_percentages' => $overallPercentages,
            'rating_distribution' => $overallPercentages,
            'strengths_weaknesses' => $strengthsWeaknesses,
            'descriptive_analysis' => $descriptiveAnalysis,
            'recommendations' => $recommendations,
            'teacher_breakdown' => $teacherBreakdown,
            'title_info' => [
                'subject_name' => $subject->name,
                'subject_code' => $subject->code,
                'semester' => 'Semester ' . $subject->semester,
                'teachers' => $subject->teachers->pluck('name')->implode(', ') ?: 'Not assigned',
                'academic_year' => '2025-26',
                'report_date' => now()->format('F d, Y'),
                'total_responses' => $totalResponses,
            ],
        ];
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
                ];
            } elseif ($stats['average'] < 3.5) {
                $weaknesses[] = [
                    'question' => $stats['question'],
                    'average' => $stats['average'],
                ];
            }
        }

        return [
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
        ];
    }

    private function generateDescriptiveAnalysis($subject, $totalResponses, $overallAverage, $percentages, $questionStats)
    {
        $analysis = [];

        $analysis[] = "This comprehensive analysis is based on **{$totalResponses} student feedback responses** collected for **{$subject->name} ({$subject->code})** during the academic year 2025-26.";
        
        $analysis[] = "The overall average rating for this subject is **{$overallAverage}/5.0**, indicating a " . 
                     ($overallAverage >= 4.0 ? "strong positive" : ($overallAverage >= 3.0 ? "satisfactory" : "needs improvement")) . 
                     " level of student satisfaction with the course.";

        $stronglyAgree = $percentages['Strongly Agree'] ?? 0;
        $agree = $percentages['Agree'] ?? 0;
        $positive = $stronglyAgree + $agree;
        
        $analysis[] = "**{$positive}%** of all responses were positive (Strongly Agree + Agree), with **{$stronglyAgree}%** expressing strong agreement, reflecting overall student sentiment toward the subject.";

        // Analyze course structure
        $courseQuestions = array_filter($questionStats, function($key) {
            return strpos($key, 'learning_') === 0 || strpos($key, 'content_') === 0 || 
                   strpos($key, 'opportunities_') === 0 || strpos($key, 'access_') === 0 || 
                   strpos($key, 'teaching_') === 0 || strpos($key, 'diverse_') === 0;
        }, ARRAY_FILTER_USE_KEY);
        
        if (!empty($courseQuestions)) {
            $courseAvg = round(array_sum(array_column($courseQuestions, 'average')) / count($courseQuestions), 2);
            $analysis[] = "Course structure and design received an average rating of **{$courseAvg}/5.0**, indicating " . 
                         ($courseAvg >= 4.0 ? "excellent" : ($courseAvg >= 3.5 ? "good" : "acceptable")) . 
                         " organization of course objectives, materials, and assignments.";
        }

        // Analyze instructor effectiveness
        $instructorQuestions = array_filter($questionStats, function($key) {
            return strpos($key, 'instructor_') === 0;
        }, ARRAY_FILTER_USE_KEY);
        
        if (!empty($instructorQuestions)) {
            $instructorAvg = round(array_sum(array_column($instructorQuestions, 'average')) / count($instructorQuestions), 2);
            $analysis[] = "Teaching effectiveness and instructor-related parameters achieved an average rating of **{$instructorAvg}/5.0**, reflecting student perception of instruction quality for this subject.";
        }

        // Analyze learning environment
        $envQuestions = array_filter($questionStats, function($key) {
            return strpos($key, 'access_materials') === 0;
        }, ARRAY_FILTER_USE_KEY);
        
        if (!empty($envQuestions)) {
            $envAvg = round(array_sum(array_column($envQuestions, 'average')) / count($envQuestions), 2);
            $analysis[] = "Learning environment and resource adequacy scored **{$envAvg}/5.0**, indicating " . 
                         ($envAvg >= 4.0 ? "excellent" : ($envAvg >= 3.5 ? "satisfactory" : "adequate")) . 
                         " support infrastructure for the subject.";
        }

        return $analysis;
    }

    private function generateRecommendations($overallAverage, $strengthsWeaknesses)
    {
        $recommendations = [];

        if ($overallAverage < 4.0) {
            $recommendations[] = "Consider conducting focused improvement workshops for areas scoring below 4.0 to enhance overall subject delivery and student satisfaction.";
        }

        if (!empty($strengthsWeaknesses['weaknesses'])) {
            $recommendations[] = "Address the identified weak areas through targeted interventions, such as additional teaching resources, modified teaching methods, or enhanced student support mechanisms.";
        }

        if (!empty($strengthsWeaknesses['strengths'])) {
            $recommendations[] = "Continue and expand upon the identified strong areas. Document successful practices for replication in other courses.";
        }

        $recommendations[] = "Regularly review and update course materials and teaching methodologies to ensure continued relevance and effectiveness.";
        
        $recommendations[] = "Encourage continuous feedback from students throughout the semester to enable timely course corrections and improvements.";

        $recommendations[] = "Foster collaboration among faculty teaching this subject to share best practices and coordinate content delivery.";

        return $recommendations;
    }

    private function getTeacherBreakdown($responses)
    {
        $teacherData = [];

        foreach ($responses as $response) {
            if ($response->formAssignment && $response->formAssignment->teacher) {
                $teacherId = $response->formAssignment->teacher_id;
                $teacherName = $response->formAssignment->teacher->name;

                if (!isset($teacherData[$teacherId])) {
                    $teacherData[$teacherId] = [
                        'name' => $teacherName,
                        'response_count' => 0,
                        'total_rating' => 0,
                        'rating_count' => 0,
                    ];
                }

                $teacherData[$teacherId]['response_count']++;

                // Calculate average rating for this response
                if (isset($response->responses) && is_array($response->responses)) {
                    foreach ($response->responses as $key => $responseData) {
                        // Handle both formats: direct rating or object with 'rating' key
                        if (is_array($responseData) && isset($responseData['rating'])) {
                            $ratingText = $responseData['rating'];
                        } elseif (is_string($responseData)) {
                            $ratingText = $responseData;
                        } else {
                            continue;
                        }
                        
                        // Convert text rating to numeric value
                        $value = $this->convertRatingToNumeric($ratingText);
                        
                        if ($value > 0) {
                            $teacherData[$teacherId]['total_rating'] += $value;
                            $teacherData[$teacherId]['rating_count']++;
                        }
                    }
                }
            }
        }

        // Calculate averages
        foreach ($teacherData as $teacherId => $data) {
            $teacherData[$teacherId]['average_rating'] = $data['rating_count'] > 0 
                ? round($data['total_rating'] / $data['rating_count'], 2) 
                : 0;
        }

        return array_values($teacherData);
    }

    /**
     * Convert text rating to numeric value
     */
    private function convertRatingToNumeric($ratingText)
    {
        $ratingMap = [
            'Strongly Agree' => 5,
            'Agree' => 4,
            'Neutral' => 3,
            'Disagree' => 2,
            'Strongly Disagree' => 1,
        ];

        return $ratingMap[$ratingText] ?? 0;
    }
}
