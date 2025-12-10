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
            // Placement status from CSV
            $table->enum('placed_by_charusat', ['YES', 'NO'])->nullable()->after('status');
            $table->boolean('has_offer_letter')->default(false)->after('placed_by_charusat');
            $table->decimal('stipend', 10, 2)->nullable()->after('package');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_placements', function (Blueprint $table) {
            $table->dropColumn([
                'placed_by_charusat',
                'has_offer_letter',
                'stipend',
            ]);
        });
    }
};
