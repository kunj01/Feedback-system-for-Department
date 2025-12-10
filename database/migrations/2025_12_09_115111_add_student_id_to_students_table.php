<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add student_id column as nullable first
        Schema::table('students', function (Blueprint $table) {
            $table->string('student_id', 50)->nullable()->after('id');
        });

        // Step 2: Backfill student_id for existing records
        // Generate student_id from department code + batch + padded id
        DB::statement("
            UPDATE students s
            LEFT JOIN departments d ON s.department_id = d.id
            SET s.student_id = CONCAT(
                COALESCE(UPPER(SUBSTRING(d.code, 1, 3)), 'STD'),
                COALESCE(s.batch, '2025'),
                LPAD(s.id, 4, '0')
            )
            WHERE s.student_id IS NULL
        ");

        // Step 3: Make student_id NOT NULL and add unique constraint
        Schema::table('students', function (Blueprint $table) {
            $table->string('student_id', 50)->nullable(false)->unique()->change();
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['student_id']);
            $table->dropIndex(['student_id']);
            $table->dropColumn('student_id');
        });
    }
};
