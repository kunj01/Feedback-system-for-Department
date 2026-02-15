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
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->integer('semester');
            $table->string('branch', 50);
            $table->integer('division_number');
            $table->string('name', 50)->unique(); // e.g., "4-IT-2"
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['semester', 'branch', 'division_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
