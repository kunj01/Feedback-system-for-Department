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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->unsignedBigInteger('department_id')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('department_id');
            $table->json('extra_profile')->nullable()->after('is_active');
            $table->softDeletes();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['phone', 'department_id', 'is_active', 'extra_profile']);
            $table->dropSoftDeletes();
        });
    }
};
