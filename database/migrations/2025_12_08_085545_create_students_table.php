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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('roll_no', 50)->nullable();
            $table->string('registration_no', 50)->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['M', 'F', 'O'])->nullable();
            $table->string('father_name', 255)->nullable();
            $table->string('mother_name', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('contact', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('course', 100)->nullable();
            $table->year('batch')->nullable();
            $table->string('academic_year', 20)->nullable();
            $table->decimal('cgpa', 4, 2)->nullable();
            $table->json('academic_details')->nullable();
            $table->enum('training_status', ['NOT_ASSIGNED', 'IN_TRAINING', 'COMPLETED'])->default('NOT_ASSIGNED');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
