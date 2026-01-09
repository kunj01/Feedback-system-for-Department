<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('speaker_feedback', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn([
                'event_quality',
                'venue_facilities',
                'hospitality',
                'overall_experience',
                'suggestions',
                'rating'
            ]);
        });

        Schema::table('speaker_feedback', function (Blueprint $table) {
            // Add 10 curriculum feedback questions (1-5 scale)
            $table->integer('q1_content_of_syllabus')->nullable()->comment('Content of syllabus');
            $table->integer('q2_relevance_to_industry')->nullable()->comment('Relevance of syllabus to industry/research requirements');
            $table->integer('q3_course_outcomes')->nullable()->comment('Course outcomes are well defined');
            $table->integer('q4_reading_materials')->nullable()->comment('Sufficient reading materials and digital resources provided');
            $table->integer('q5_advanced_topics')->nullable()->comment('Incorporation of advanced topics');
            $table->integer('q6_pedagogy')->nullable()->comment('Pedagogy proposed');
            $table->integer('q7_theory_practical_balance')->nullable()->comment('Have a desired balance between theory and practical');
            $table->integer('q8_assessment_methods')->nullable()->comment('Assessment methods are fair, measuring the outcomes');
            $table->integer('q9_project_component')->nullable()->comment('Project component in the course, if applicable');
            $table->integer('q10_industrial_training')->nullable()->comment('Industrial training/practical exposure in the course, if applicable');
            
            // Optional comments field
            $table->text('additional_comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('speaker_feedback', function (Blueprint $table) {
            $table->dropColumn([
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
                'additional_comments'
            ]);
        });

        Schema::table('speaker_feedback', function (Blueprint $table) {
            $table->text('event_quality')->nullable();
            $table->text('venue_facilities')->nullable();
            $table->text('hospitality')->nullable();
            $table->text('overall_experience')->nullable();
            $table->text('suggestions')->nullable();
            $table->integer('rating')->nullable();
        });
    }
};
