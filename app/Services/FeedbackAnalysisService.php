<?php

namespace App\Services;

use App\Models\SpeakerFeedback;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class FeedbackAnalysisService
{
    /**
     * Rating scale mapping
     * 5 = Excellent, 4 = Very Good, 3 = Good, 2 = Satisfactory, 1 = Needs Improvement
     */
    private const RATING_LABELS = [
        5 => 'Excellent',
        4 => 'Very Good',
        3 => 'Good',
        2 => 'Satisfactory',
        1 => 'Needs Improvement'
    ];

    /**
     * Question labels for the 10 fixed curriculum questions
     */
    private const QUESTION_LABELS = [
        'q1_content_of_syllabus' => 'Content of syllabus',
        'q2_relevance_to_industry' => 'Relevance of syllabus to industry/research requirements',
        'q3_course_outcomes' => 'Course outcomes are well defined',
        'q4_reading_materials' => 'Sufficient reading materials and digital resources provided',
        'q5_advanced_topics' => 'Incorporation of advanced topics',
        'q6_pedagogy' => 'Pedagogy proposed',
        'q7_theory_practical_balance' => 'Balance between theory and practical',
        'q8_assessment_methods' => 'Assessment methods are fair and outcome-based',
        'q9_project_component' => 'Project component in the course (if applicable)',
        'q10_industrial_training' => 'Industrial training / practical exposure (if applicable)',
    ];

    /**
     * Generate complete NAAC-compliant analysis report
     */
    public function generateAnalysisReport(
        string $academicYear = null,
        string $department = null,
        string $institute = null
    ): array {
        $academicYear = $academicYear ?? $this->getCurrentAcademicYear();
        $department = $department ?? 'Department of Computer Science & Engineering';
        $institute = $institute ?? 'Institute Name';

        // Get all feedback data
        $feedbackData = SpeakerFeedback::all();

        if ($feedbackData->isEmpty()) {
            return [
                'error' => 'No feedback data available for analysis',
                'has_data' => false
            ];
        }

        // Calculate statistics
        $statistics = $this->calculateStatistics($feedbackData);
        $overallSummary = $this->calculateOverallSummary($statistics);
        $descriptiveAnalysis = $this->generateDescriptiveAnalysis($statistics, $overallSummary);
        $interpretations = $this->generateInterpretations($statistics, $overallSummary);
        $recommendations = $this->generateRecommendations($statistics, $overallSummary);
        $chartData = $this->prepareChartData($statistics);

        return [
            'has_data' => true,
            'title_info' => [
                'institute' => $institute,
                'department' => $department,
                'academic_year' => $academicYear,
                'report_date' => Carbon::now()->format('F d, Y'),
                'total_responses' => $feedbackData->count(),
            ],
            'descriptive_analysis' => $descriptiveAnalysis,
            'statistics' => $statistics,
            'overall_summary' => $overallSummary,
            'interpretations' => $interpretations,
            'recommendations' => $recommendations,
            'chart_data' => $chartData,
            'question_labels' => self::QUESTION_LABELS,
            'rating_labels' => self::RATING_LABELS,
        ];
    }

    /**
     * Calculate statistical data for each question
     */
    private function calculateStatistics(Collection $feedbackData): array
    {
        $statistics = [];
        $totalResponses = $feedbackData->count();

        foreach (self::QUESTION_LABELS as $questionKey => $questionLabel) {
            $ratingCounts = [
                5 => 0, // Excellent
                4 => 0, // Very Good
                3 => 0, // Good
                2 => 0, // Satisfactory
                1 => 0, // Needs Improvement
            ];

            // Count ratings for this question
            foreach ($feedbackData as $feedback) {
                $rating = $feedback->{$questionKey};
                if ($rating >= 1 && $rating <= 5) {
                    $ratingCounts[$rating]++;
                }
            }

            // Calculate percentages
            $ratingPercentages = [];
            foreach ($ratingCounts as $rating => $count) {
                $ratingPercentages[$rating] = $totalResponses > 0 
                    ? round(($count / $totalResponses) * 100, 2) 
                    : 0;
            }

            // Calculate average rating
            $totalScore = 0;
            foreach ($ratingCounts as $rating => $count) {
                $totalScore += $rating * $count;
            }
            $averageRating = $totalResponses > 0 
                ? round($totalScore / $totalResponses, 2) 
                : 0;

            $statistics[$questionKey] = [
                'label' => $questionLabel,
                'counts' => $ratingCounts,
                'percentages' => $ratingPercentages,
                'average' => $averageRating,
                'total_responses' => $totalResponses,
            ];
        }

        return $statistics;
    }

    /**
     * Calculate overall consolidated summary
     */
    private function calculateOverallSummary(array $statistics): array
    {
        $overallCounts = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0,
        ];

        $totalQuestions = count($statistics);
        $totalResponses = 0;

        foreach ($statistics as $stat) {
            foreach ($stat['counts'] as $rating => $count) {
                $overallCounts[$rating] += $count;
            }
            $totalResponses = $stat['total_responses']; // Same for all
        }

        $totalDataPoints = $totalQuestions * $totalResponses;
        
        $overallPercentages = [];
        foreach ($overallCounts as $rating => $count) {
            $overallPercentages[$rating] = $totalDataPoints > 0 
                ? round(($count / $totalDataPoints) * 100, 2) 
                : 0;
        }

        // Calculate overall average
        $totalScore = 0;
        foreach ($overallCounts as $rating => $count) {
            $totalScore += $rating * $count;
        }
        $overallAverage = $totalDataPoints > 0 
            ? round($totalScore / $totalDataPoints, 2) 
            : 0;

        return [
            'counts' => $overallCounts,
            'percentages' => $overallPercentages,
            'average' => $overallAverage,
            'total_questions' => $totalQuestions,
            'total_responses' => $totalResponses,
        ];
    }

    /**
     * Generate descriptive analysis bullet points
     */
    private function generateDescriptiveAnalysis(array $statistics, array $overallSummary): array
    {
        $analysis = [];
        
        $excellentVeryGoodPercent = $overallSummary['percentages'][5] + $overallSummary['percentages'][4];
        $needsImprovementPercent = $overallSummary['percentages'][1];
        
        // Point 1: Overall feedback trend
        if ($excellentVeryGoodPercent >= 70) {
            $analysis[] = "The curriculum has received overwhelmingly positive feedback from external industry and academic experts, with " . 
                         number_format($excellentVeryGoodPercent, 1) . "% of responses falling in the 'Excellent' and 'Very Good' categories, " .
                         "indicating strong alignment with contemporary industry standards and academic rigor.";
        } elseif ($excellentVeryGoodPercent >= 50) {
            $analysis[] = "The curriculum has received moderately positive feedback from external experts, with " . 
                         number_format($excellentVeryGoodPercent, 1) . "% of responses in the 'Excellent' and 'Very Good' categories, " .
                         "suggesting reasonable alignment with industry expectations while highlighting opportunities for enhancement.";
        } else {
            $analysis[] = "The curriculum feedback from external experts indicates significant scope for improvement, with " . 
                         number_format($excellentVeryGoodPercent, 1) . "% of responses in the 'Excellent' and 'Very Good' categories, " .
                         "necessitating comprehensive curriculum revision to meet industry and academic standards.";
        }

        // Point 2: Strengths identification
        $topQuestions = collect($statistics)->sortByDesc('average')->take(3);
        $strengthAreas = [];
        foreach ($topQuestions as $key => $stat) {
            if ($stat['average'] >= 4.0) {
                $strengthAreas[] = strtolower($stat['label']);
            }
        }
        
        if (!empty($strengthAreas)) {
            $analysis[] = "Notable strengths identified by external experts include: " . 
                         $this->formatList($strengthAreas) . ", demonstrating the curriculum's effectiveness in these critical areas.";
        }

        // Point 3: Industry relevance
        $relevanceStat = $statistics['q2_relevance_to_industry'];
        $relevanceHighPercent = $relevanceStat['percentages'][5] + $relevanceStat['percentages'][4];
        
        if ($relevanceHighPercent >= 70) {
            $analysis[] = "Industry relevance of the curriculum is particularly commendable, with " . 
                         number_format($relevanceHighPercent, 1) . "% of experts rating it as 'Excellent' or 'Very Good', " .
                         "confirming that the syllabus aligns well with current industry demands and research requirements.";
        } elseif ($relevanceHighPercent >= 50) {
            $analysis[] = "Industry relevance of the curriculum shows moderate approval with " . 
                         number_format($relevanceHighPercent, 1) . "% positive ratings, " .
                         "suggesting the need for enhanced industry-academia collaboration in curriculum development.";
        } else {
            $analysis[] = "Industry relevance requires significant attention, as only " . 
                         number_format($relevanceHighPercent, 1) . "% of experts provided positive ratings, " .
                         "indicating a substantial gap between curriculum content and industry expectations.";
        }

        // Point 4: Pedagogy and assessment methods
        $pedagogyStat = $statistics['q6_pedagogy'];
        $assessmentStat = $statistics['q8_assessment_methods'];
        $avgPedagogyAssessment = ($pedagogyStat['average'] + $assessmentStat['average']) / 2;
        
        if ($avgPedagogyAssessment >= 4.0) {
            $analysis[] = "Teaching methodologies and assessment strategies have been well-received by experts, " .
                         "with an average rating of " . number_format($avgPedagogyAssessment, 2) . "/5.00, " .
                         "reflecting effective pedagogical approaches and fair outcome-based evaluation methods.";
        } else {
            $analysis[] = "Teaching methodologies and assessment strategies require refinement, " .
                         "as indicated by an average rating of " . number_format($avgPedagogyAssessment, 2) . "/5.00, " .
                         "suggesting the need for innovative pedagogical approaches and more robust assessment frameworks.";
        }

        // Point 5: Practical exposure and industrial training
        $practicalStat = $statistics['q10_industrial_training'];
        $projectStat = $statistics['q9_project_component'];
        $practicalHighPercent = ($practicalStat['percentages'][5] + $practicalStat['percentages'][4]);
        
        if ($practicalHighPercent >= 60) {
            $analysis[] = "Practical exposure and industrial training components are recognized as strengths, " .
                         "with " . number_format($practicalHighPercent, 1) . "% positive ratings, " .
                         "demonstrating effective integration of hands-on learning experiences with theoretical knowledge.";
        } else {
            $analysis[] = "Enhancement of practical exposure and industrial training is strongly recommended, " .
                         "as only " . number_format($practicalHighPercent, 1) . "% of experts provided positive ratings, " .
                         "highlighting the need for stronger industry partnerships and experiential learning opportunities.";
        }

        // Point 6: Areas for improvement
        $lowestQuestions = collect($statistics)->sortBy('average')->take(2);
        $improvementAreas = [];
        foreach ($lowestQuestions as $key => $stat) {
            if ($stat['average'] < 3.5) {
                $improvementAreas[] = strtolower($stat['label']);
            }
        }
        
        if (!empty($improvementAreas)) {
            $analysis[] = "Specific areas identified for improvement include: " . 
                         $this->formatList($improvementAreas) . ", requiring focused attention and strategic interventions to enhance curriculum quality.";
        } else {
            $analysis[] = "Overall, the curriculum demonstrates satisfactory performance across all parameters, " .
                         "with continuous monitoring and periodic updates recommended to maintain relevance and quality standards.";
        }

        return $analysis;
    }

    /**
     * Generate interpretation and inference
     */
    private function generateInterpretations(array $statistics, array $overallSummary): array
    {
        $interpretations = [];
        
        $overallAverage = $overallSummary['average'];
        
        // Overall interpretation
        if ($overallAverage >= 4.5) {
            $interpretations['overall'] = "The comprehensive analysis of external expert feedback reveals exceptional curriculum quality, " .
                                        "with an overall average rating of " . number_format($overallAverage, 2) . " out of 5.00. " .
                                        "This outstanding performance indicates that the curriculum successfully integrates academic rigor with industry relevance, " .
                                        "contemporary pedagogical approaches, and adequate practical exposure. The curriculum demonstrates strong alignment " .
                                        "with NAAC/NBA benchmarks and reflects the institution's commitment to quality education.";
        } elseif ($overallAverage >= 4.0) {
            $interpretations['overall'] = "The analysis indicates very good curriculum quality, with an overall average rating of " . 
                                        number_format($overallAverage, 2) . " out of 5.00. " .
                                        "External experts recognize the curriculum's strengths in content organization, industry alignment, and pedagogical design. " .
                                        "The feedback suggests that the curriculum meets most NAAC/NBA quality parameters while providing valuable insights " .
                                        "for targeted enhancements in specific areas.";
        } elseif ($overallAverage >= 3.5) {
            $interpretations['overall'] = "The curriculum receives a good overall rating of " . number_format($overallAverage, 2) . " out of 5.00, " .
                                        "indicating satisfactory performance with notable scope for improvement. " .
                                        "While the fundamental curriculum structure is sound, external experts suggest enhancements in industry integration, " .
                                        "advanced topics incorporation, and practical exposure to better align with contemporary requirements and NAAC/NBA standards.";
        } else {
            $interpretations['overall'] = "The analysis reveals an overall rating of " . number_format($overallAverage, 2) . " out of 5.00, " .
                                        "indicating substantial need for curriculum revision and enhancement. " .
                                        "External experts have identified significant gaps in various parameters including industry relevance, practical exposure, " .
                                        "and advanced topic integration. Comprehensive curriculum restructuring is recommended to meet NAAC/NBA accreditation standards " .
                                        "and industry expectations.";
        }

        // Specific strengths and gaps
        $highPerformers = collect($statistics)->where('average', '>=', 4.0)->count();
        $lowPerformers = collect($statistics)->where('average', '<', 3.5)->count();
        
        $interpretations['balance'] = "Out of 10 curriculum parameters evaluated, " . $highPerformers . 
                                     " parameters received ratings of 4.0 or above, indicating strong performance, " .
                                     "while " . $lowPerformers . " parameters scored below 3.5, requiring focused improvement initiatives. ";
        
        if ($lowPerformers == 0) {
            $interpretations['balance'] .= "The consistent high performance across all parameters reflects comprehensive curriculum excellence " .
                                          "and effective implementation of quality assurance mechanisms.";
        } elseif ($lowPerformers <= 2) {
            $interpretations['balance'] .= "The curriculum demonstrates overall strength with specific areas for targeted enhancement, " .
                                          "enabling focused quality improvement initiatives.";
        } else {
            $interpretations['balance'] .= "The uneven performance across parameters necessitates a systematic curriculum review process " .
                                          "with prioritized action plans for low-performing areas.";
        }

        return $interpretations;
    }

    /**
     * Generate actionable recommendations
     */
    private function generateRecommendations(array $statistics, array $overallSummary): array
    {
        $recommendations = [];
        
        // Recommendation 1: Industry collaboration
        $relevanceAvg = $statistics['q2_relevance_to_industry']['average'];
        if ($relevanceAvg < 4.0) {
            $recommendations[] = [
                'title' => 'Enhance Industry-Academia Collaboration',
                'description' => 'Establish formal partnerships with leading industry organizations to ensure curriculum content ' .
                               'remains aligned with evolving industry standards, emerging technologies, and market demands. ' .
                               'Form an Industry Advisory Board comprising experienced professionals to provide regular feedback ' .
                               'and guidance on curriculum updates.',
                'priority' => 'High'
            ];
        }

        // Recommendation 2: Advanced topics and emerging technologies
        $advancedTopicsAvg = $statistics['q5_advanced_topics']['average'];
        if ($advancedTopicsAvg < 4.0) {
            $recommendations[] = [
                'title' => 'Integrate Advanced and Emerging Technologies',
                'description' => 'Incorporate cutting-edge topics such as Artificial Intelligence, Machine Learning, Cloud Computing, ' .
                               'IoT, Blockchain, Cybersecurity, and Data Science into the curriculum. Regularly update course content ' .
                               'to reflect technological advancements and industry trends. Introduce specialized electives focusing on ' .
                               'emerging domains.',
                'priority' => 'High'
            ];
        }

        // Recommendation 3: Practical exposure and industrial training
        $practicalAvg = $statistics['q10_industrial_training']['average'];
        $projectAvg = $statistics['q9_project_component']['average'];
        if ($practicalAvg < 4.0 || $projectAvg < 4.0) {
            $recommendations[] = [
                'title' => 'Strengthen Practical Training and Industry Exposure',
                'description' => 'Mandate internships in reputed organizations for all students, organize regular industry visits, ' .
                               'conduct hands-on workshops led by industry experts, and establish innovation labs equipped with ' .
                               'latest tools and technologies. Increase the weightage of project-based learning and capstone projects ' .
                               'addressing real-world problems.',
                'priority' => 'High'
            ];
        }

        // Recommendation 4: Pedagogy and teaching methods
        $pedagogyAvg = $statistics['q6_pedagogy']['average'];
        if ($pedagogyAvg < 4.0) {
            $recommendations[] = [
                'title' => 'Adopt Innovative Pedagogical Approaches',
                'description' => 'Implement active learning methodologies including flipped classrooms, problem-based learning, ' .
                               'collaborative projects, and blended learning approaches. Encourage faculty to use modern educational ' .
                               'technologies, interactive tools, and online learning platforms. Conduct regular faculty development ' .
                               'programs on contemporary teaching methods.',
                'priority' => 'Medium'
            ];
        }

        // Recommendation 5: Assessment and evaluation
        $assessmentAvg = $statistics['q8_assessment_methods']['average'];
        if ($assessmentAvg < 4.0) {
            $recommendations[] = [
                'title' => 'Enhance Assessment Mechanisms',
                'description' => 'Develop comprehensive outcome-based assessment strategies including continuous evaluation, ' .
                               'practical assessments, project evaluations, peer reviews, and industry-validated assessments. ' .
                               'Ensure assessments accurately measure attainment of course outcomes and program outcomes aligned ' .
                               'with NBA/NAAC requirements.',
                'priority' => 'Medium'
            ];
        }

        // Recommendation 6: Resources and learning materials
        $resourcesAvg = $statistics['q4_reading_materials']['average'];
        if ($resourcesAvg < 4.0) {
            $recommendations[] = [
                'title' => 'Augment Learning Resources and Digital Infrastructure',
                'description' => 'Provide access to premium online learning platforms, industry-standard software tools, ' .
                               'e-books, research journals, and digital libraries. Develop comprehensive course materials including ' .
                               'video lectures, interactive modules, case studies, and reference materials. Ensure adequate ' .
                               'laboratory infrastructure with updated hardware and software.',
                'priority' => 'Medium'
            ];
        }

        // Recommendation 7: Theory-practical balance
        $balanceAvg = $statistics['q7_theory_practical_balance']['average'];
        if ($balanceAvg < 4.0) {
            $recommendations[] = [
                'title' => 'Optimize Theory-Practical Balance',
                'description' => 'Redesign courses to achieve optimal balance between theoretical concepts and practical applications. ' .
                               'Every theoretical concept should be reinforced through hands-on exercises, laboratory sessions, ' .
                               'or real-world case studies. Increase practical contact hours and ensure laboratory exercises ' .
                               'align with theoretical content.',
                'priority' => 'Medium'
            ];
        }

        // Recommendation 8: Continuous improvement
        $recommendations[] = [
            'title' => 'Establish Continuous Curriculum Review Mechanism',
            'description' => 'Institute a formal curriculum review committee comprising faculty, industry experts, alumni, and students. ' .
                           'Conduct annual curriculum reviews incorporating feedback from all stakeholders. Implement a systematic ' .
                           'process for curriculum updates, ensuring agility in adapting to changing requirements while maintaining ' .
                           'academic standards and accreditation compliance.',
            'priority' => 'High'
        ];

        // Add more specific recommendations based on low-performing areas
        $lowestQuestion = collect($statistics)->sortBy('average')->first();
        if ($lowestQuestion['average'] < 3.0) {
            $recommendations[] = [
                'title' => 'Priority Focus on Low-Performing Area: ' . $lowestQuestion['label'],
                'description' => 'The parameter "' . $lowestQuestion['label'] . '" requires immediate attention with an average rating ' .
                               'of ' . number_format($lowestQuestion['average'], 2) . '/5.00. Form a dedicated task force to analyze ' .
                               'root causes, develop an action plan, implement corrective measures, and monitor progress through ' .
                               'periodic reviews.',
                'priority' => 'Critical'
            ];
        }

        return $recommendations;
    }

    /**
     * Prepare data for charts
     */
    private function prepareChartData(array $statistics): array
    {
        $chartData = [];
        
        foreach ($statistics as $questionKey => $stat) {
            $chartData[$questionKey] = [
                'label' => $this->truncateLabel($stat['label']),
                'full_label' => $stat['label'],
                'data' => [
                    'excellent' => $stat['percentages'][5],
                    'very_good' => $stat['percentages'][4],
                    'good' => $stat['percentages'][3],
                    'satisfactory' => $stat['percentages'][2],
                    'needs_improvement' => $stat['percentages'][1],
                ],
                'average' => $stat['average'],
            ];
        }
        
        return $chartData;
    }

    /**
     * Get current academic year
     */
    private function getCurrentAcademicYear(): string
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        
        // Academic year typically runs from June/July to May/June
        if ($currentMonth >= 6) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }

    /**
     * Format list of items with proper grammar
     */
    private function formatList(array $items): string
    {
        if (count($items) == 0) {
            return '';
        }
        if (count($items) == 1) {
            return $items[0];
        }
        if (count($items) == 2) {
            return $items[0] . ' and ' . $items[1];
        }
        
        $lastItem = array_pop($items);
        return implode(', ', $items) . ', and ' . $lastItem;
    }

    /**
     * Truncate label for chart display
     */
    private function truncateLabel(string $label, int $length = 50): string
    {
        if (strlen($label) <= $length) {
            return $label;
        }
        
        return substr($label, 0, $length - 3) . '...';
    }

    /**
     * Get rating label by numeric value
     */
    public static function getRatingLabel(int $rating): string
    {
        return self::RATING_LABELS[$rating] ?? 'Unknown';
    }

    /**
     * Get all rating labels
     */
    public static function getRatingLabels(): array
    {
        return self::RATING_LABELS;
    }

    /**
     * Get all question labels
     */
    public static function getQuestionLabels(): array
    {
        return self::QUESTION_LABELS;
    }
}
