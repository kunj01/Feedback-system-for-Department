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
        Schema::table('speakers', function (Blueprint $table) {
            $table->string('faculty_approval_status')->default('pending')->after('approval_status'); // pending, approved, rejected
            $table->foreignId('faculty_approved_by')->nullable()->constrained('users')->onDelete('set null')->after('faculty_approval_status');
            $table->timestamp('faculty_approved_at')->nullable()->after('faculty_approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('speakers', function (Blueprint $table) {
            $table->dropForeign(['faculty_approved_by']);
            $table->dropColumn(['faculty_approval_status', 'faculty_approved_by', 'faculty_approved_at']);
        });
    }
};
