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
            $table->dateTime('start_date')->nullable()->after('form_title');
            $table->dateTime('end_date')->nullable()->after('start_date');
            $table->integer('grace_period_hours')->default(0)->after('end_date')->comment('Extra hours after end_date to allow submissions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_assignments', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'grace_period_hours']);
        });
    }
};
