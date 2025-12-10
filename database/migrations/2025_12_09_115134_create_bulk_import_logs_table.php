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
        Schema::create('bulk_import_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('import_type', ['STUDENTS', 'ASSIGNMENTS', 'REPORTS'])->default('STUDENTS');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('filename', 255);
            $table->integer('total_rows')->unsigned()->default(0);
            $table->integer('created_count')->unsigned()->default(0);
            $table->integer('updated_count')->unsigned()->default(0);
            $table->integer('skipped_count')->unsigned()->default(0);
            $table->json('errors')->nullable();
            $table->enum('status', ['DRY_RUN', 'COMPLETED', 'FAILED'])->default('DRY_RUN');
            $table->text('summary')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('uploaded_by');
            $table->index('status');
            $table->index('import_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_import_logs');
    }
};
