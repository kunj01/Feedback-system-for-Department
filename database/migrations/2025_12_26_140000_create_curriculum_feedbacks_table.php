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
        Schema::create('curriculum_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('respondent_type'); // 'academician', 'teacher', 'industry'
            
            // Respondent Information
            $table->string('institute')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('program')->nullable(); // e.g., B.Tech. (IT)
            $table->string('course')->nullable(); // Course name if applicable
            
            // 10 Specific Criteria (1-5 rating: 5=Excellent, 4=Very Good, 3=Good, 2=Satisfactory, 1=Needs Improvement)
            $table->integer('content_of_syllabus')->nullable();
            $table->integer('relevance_to_industry')->nullable();
            $table->integer('course_outcomes_defined')->nullable();
            $table->integer('reading_materials_resources')->nullable();
            $table->integer('advanced_topics')->nullable();
            $table->integer('pedagogy_proposed')->nullable();
            $table->integer('theory_practical_balance')->nullable();
            $table->integer('assessment_methods')->nullable();
            $table->integer('project_component')->nullable();
            $table->integer('industrial_training')->nullable();
            
            // Additional Feedback
            $table->text('additional_suggestions')->nullable();
            
            // Metadata
            $table->string('academic_year')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // If logged in user
            $table->string('ip_address')->nullable();
            $table->string('status')->default('submitted'); // submitted, reviewed
            
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('respondent_type');
            $table->index('academic_year');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_feedbacks');
    }
};
