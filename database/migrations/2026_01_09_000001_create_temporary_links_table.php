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
        Schema::create('temporary_links', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('token', 64)->unique();
            $table->string('type', 50)->index(); // e.g., 'speaker_feedback', 'password_reset', etc.
            $table->timestamp('expires_at')->index();
            $table->timestamp('used_at')->nullable();
            $table->json('metadata')->nullable(); // Store related IDs, extra data
            $table->timestamps();

            // Composite index for common queries
            $table->index(['token', 'expires_at', 'used_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_links');
    }
};
