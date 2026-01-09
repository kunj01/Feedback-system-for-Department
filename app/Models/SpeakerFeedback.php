<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpeakerFeedback extends Model
{
    protected $table = 'speaker_feedback';

    protected $fillable = [
        'speaker_id',
        'q1_content_of_syllabus',
        'q2_relevance_to_industry',
        'q3_course_outcomes',
        'q4_reading_materials',
        'q5_advanced_topics',
        'q6_pedagogy',
        'q7_theory_practical_balance',
        'q8_assessment_methods',
        'q9_project_component',
        'q10_industrial_training',
        'additional_comments',
    ];

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    /**
     * Get the average rating across all questions
     */
    public function getAverageRatingAttribute(): float
    {
        $questions = [
            $this->q1_content_of_syllabus,
            $this->q2_relevance_to_industry,
            $this->q3_course_outcomes,
            $this->q4_reading_materials,
            $this->q5_advanced_topics,
            $this->q6_pedagogy,
            $this->q7_theory_practical_balance,
            $this->q8_assessment_methods,
            $this->q9_project_component,
            $this->q10_industrial_training,
        ];

        $validRatings = array_filter($questions, fn($rating) => $rating !== null);
        
        return count($validRatings) > 0 
            ? round(array_sum($validRatings) / count($validRatings), 2)
            : 0;
    }

    /**
     * Get question labels
     */
    public static function getQuestionLabels(): array
    {
        return [
            'q1_content_of_syllabus' => 'Content of syllabus',
            'q2_relevance_to_industry' => 'Relevance of syllabus to industry/research requirements',
            'q3_course_outcomes' => 'Course outcomes are well defined',
            'q4_reading_materials' => 'Sufficient reading materials and digital resources provided',
            'q5_advanced_topics' => 'Incorporation of advanced topics',
            'q6_pedagogy' => 'Pedagogy proposed',
            'q7_theory_practical_balance' => 'Have a desired balance between theory and practical',
            'q8_assessment_methods' => 'Assessment methods are fair, measuring the outcomes',
            'q9_project_component' => 'Project component in the course, if applicable',
            'q10_industrial_training' => 'Industrial training/practical exposure in the course, if applicable',
        ];
    }
}

