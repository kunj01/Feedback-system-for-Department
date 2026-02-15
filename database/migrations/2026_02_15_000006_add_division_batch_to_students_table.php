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
        Schema::table('students', function (Blueprint $table) {
            $table->string('enrollment_no', 50)->unique()->nullable()->after('id');
            $table->foreignId('division_id')->nullable()->after('department_id')->constrained('divisions')->onDelete('set null');
            $table->foreignId('batch_id')->nullable()->after('division_id')->constrained('batches')->onDelete('set null');
            $table->integer('semester')->nullable()->after('batch_id');
            $table->string('branch', 50)->nullable()->after('semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropForeign(['batch_id']);
            $table->dropColumn(['enrollment_no', 'division_id', 'batch_id', 'semester', 'branch']);
        });
    }
};
