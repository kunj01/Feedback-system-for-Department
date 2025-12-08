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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('guide_id')->nullable();
            $table->date('evaluation_date')->nullable();
            $table->enum('mode', ['ONLINE', 'OFFLINE', 'NA'])->default('ONLINE');
            $table->decimal('marks_out_of_15', 4, 2)->nullable();
            $table->decimal('internal_exam_marks', 5, 2)->nullable();
            $table->string('internal_exam_grade', 5)->nullable();
            $table->decimal('attendance_percent', 5, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('locked')->default(false);
            $table->boolean('approved_by_head')->default(false);
            $table->text('head_comments')->nullable();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('guide_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
