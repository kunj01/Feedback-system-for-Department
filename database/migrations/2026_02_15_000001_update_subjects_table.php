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
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'branch')) {
                $table->string('branch', 50)->after('semester');
            }
            if (!Schema::hasColumn('subjects', 'subject_type')) {
                $table->enum('subject_type', ['Lecture', 'Lab'])->default('Lecture')->after('has_lab');
            }
            if (!Schema::hasColumn('subjects', 'credits')) {
                $table->integer('credits')->default(0)->after('subject_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['branch', 'subject_type', 'credits']);
        });
    }
};
