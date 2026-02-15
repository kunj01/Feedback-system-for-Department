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
        Schema::table('faculties', function (Blueprint $table) {
            $table->string('faculty_name', 255)->after('id');
            $table->string('short_code', 10)->unique()->after('faculty_name');
            $table->string('email', 255)->nullable()->after('short_code');
            $table->string('contact', 20)->nullable()->after('email');
            $table->string('department', 50)->nullable()->after('contact');
            $table->string('designation', 100)->nullable()->after('department');
            $table->boolean('is_active')->default(true)->after('designation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faculties', function (Blueprint $table) {
            $table->dropColumn(['faculty_name', 'short_code', 'email', 'contact', 'department', 'designation', 'is_active']);
        });
    }
};
