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
        Schema::table('form_assignments', function (Blueprint $table) {
            // Drop the old unique constraint
            $table->dropUnique(['form_name', 'student_id']);
            
            // Add new unique constraint that includes teacher and subject for multi-teacher support
            // This allows multiple assignments per student for the same form (one per teacher)
            $table->unique(['form_name', 'student_id', 'teacher_id', 'subject_id'], 'form_student_teacher_subject_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_assignments', function (Blueprint $table) {
            // Drop the new unique constraint
            $table->dropUnique('form_student_teacher_subject_unique');
            
            // Restore the old unique constraint
            $table->unique(['form_name', 'student_id']);
        });
    }
};
