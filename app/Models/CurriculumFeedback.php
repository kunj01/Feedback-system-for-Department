<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurriculumFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'respondent_type',
        'institute',
        'email',
        'phone',
        'program',
        'course',
        'content_of_syllabus',
        'relevance_to_industry',
        'course_outcomes_defined',
        'reading_materials_resources',
        'advanced_topics',
        'pedagogy_proposed',
        'theory_practical_balance',
        'assessment_methods',
        'project_component',
        'industrial_training',
        'additional_suggestions',
        'academic_year',
        'user_id',
        'ip_address',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who submitted the feedback (if logged in).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by respondent type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('respondent_type', $type);
    }

    /**
     * Scope to filter by academic year.
     */
    public function scopeForAcademicYear($query, string $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the overall average rating across all criteria.
     */
    public function getOverallAverageAttribute()
    {
        $fields = [
            'content_of_syllabus',
            'relevance_to_industry',
            'course_outcomes_defined',
            'reading_materials_resources',
            'advanced_topics',
            'pedagogy_proposed',
            'theory_practical_balance',
            'assessment_methods',
            'project_component',
            'industrial_training',
        ];

        $values = collect($fields)
            ->map(fn($field) => $this->$field)
            ->filter()
            ->values();

        return $values->isEmpty() ? 0 : round($values->avg(), 2);
    }

    /**
     * Get formatted respondent type.
     */
    public function getRespondentTypeNameAttribute()
    {
        return match($this->respondent_type) {
            'academician' => 'Academician',
            'teacher' => 'Teacher',
            'industry' => 'Industry Professional',
            default => ucfirst($this->respondent_type),
        };
    }

    /**
     * Get rating label.
     */
    public static function getRatingLabel(int $rating): string
    {
        return match($rating) {
            1 => 'Needs Improvement',
            2 => 'Satisfactory',
            3 => 'Good',
            4 => 'Very Good',
            5 => 'Excellent',
            default => 'N/A',
        };
    }
    
    /**
     * Get all 10 questions.
     */
    public static function getQuestions(): array
    {
        return [
            'content_of_syllabus' => 'Content of syllabus',
            'relevance_to_industry' => 'Relevance of syllabus to industry/research requirements',
            'course_outcomes_defined' => 'Course outcomes are well defined',
            'reading_materials_resources' => 'Sufficient reading materials and digital resources provided',
            'advanced_topics' => 'Incorporation of advanced topics',
            'pedagogy_proposed' => 'Pedagogy proposed',
            'theory_practical_balance' => 'Have a desired balance between theory and practical',
            'assessment_methods' => 'Assessment methods are fair, measuring the outcomes',
            'project_component' => 'Project component in the course, if applicable',
            'industrial_training' => 'Industrial training/practical exposure in the course, if applicable',
        ];
    }
}