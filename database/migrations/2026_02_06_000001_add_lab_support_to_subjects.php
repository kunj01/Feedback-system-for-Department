<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add lab support to subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->boolean('has_lab')->default(false)->after('is_active');
        });

        // Create pivot table for subject labs and teachers
        Schema::create('subject_lab_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['subject_id', 'teacher_id']);
        });

        // Add lab indicator to form assignments
        Schema::table('form_assignments', function (Blueprint $table) {
            $table->boolean('is_lab')->default(false)->after('teacher_id');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('has_lab');
        });

        Schema::dropIfExists('subject_lab_teacher');

        Schema::table('form_assignments', function (Blueprint $table) {
            $table->dropColumn('is_lab');
        });
    }
};
