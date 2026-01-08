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

        // Step 2: Backfill student_id for existing records (SQLite compatible)
        $students = DB::table('students')->whereNull('student_id')->get();
        
        foreach ($students as $student) {
            $department = DB::table('departments')->where('id', $student->department_id)->first();
            $deptCode = $department ? strtoupper(substr($department->code, 0, 3)) : 'STD';
            $batch = $student->batch ?? '2025';
            $paddedId = str_pad($student->id, 4, '0', STR_PAD_LEFT);
            
            DB::table('students')
                ->where('id', $student->id)
                ->update(['student_id' => $deptCode . $batch . $paddedId]);
        }

        // Step 3: Make student_id NOT NULL and add unique constraint
        Schema::table('students', function (Blueprint $table) {
            $table->string('student_id', 50)->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('student_id');
        });
    }
};
