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
        Schema::table('student_placements', function (Blueprint $table) {
            $table->decimal('package_lpa', 10, 2)->nullable()->after('package');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_placements', function (Blueprint $table) {
            $table->dropColumn('package_lpa');
        });
    }
};
