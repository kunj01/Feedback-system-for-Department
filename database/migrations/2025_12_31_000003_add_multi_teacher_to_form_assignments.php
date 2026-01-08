<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_assignments', function (Blueprint $table) {
            $table->boolean('is_multi_teacher')->default(false)->after('form_title');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('set null')->after('is_multi_teacher');
            $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null')->after('subject_id');
        });
    }

    public function down(): void
    {
        Schema::table('form_assignments', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['is_multi_teacher', 'subject_id', 'teacher_id']);
        });
    }
};
