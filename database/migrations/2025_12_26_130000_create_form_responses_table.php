<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_assignment_id')->constrained('form_assignments')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('email');
            $table->string('name')->nullable();
            $table->json('responses'); // Store all form field responses
            $table->timestamps();
            
            $table->unique('form_assignment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_responses');
    }
};
