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
        Schema::table('students', function (Blueprint $table) {
            // Personal details from CSV
            $table->string('first_name', 100)->nullable()->after('user_id');
            $table->string('middle_name', 100)->nullable()->after('first_name');
            $table->string('last_name', 100)->nullable()->after('middle_name');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('personal_email', 255)->nullable()->after('email');

            // Academic scores from CSV
            $table->decimal('ssc_percentage', 5, 2)->nullable()->after('cgpa'); // 10th
            $table->decimal('hsc_percentage', 5, 2)->nullable()->after('ssc_percentage'); // 12th
            $table->decimal('diploma_percentage', 5, 2)->nullable()->after('hsc_percentage'); // Diploma
            $table->decimal('btech_cgpa_upto_5th', 4, 2)->nullable()->after('diploma_percentage'); // B.Tech CGPA (Upto 5th Sem)

            // Admission and eligibility
            $table->string('admission_type', 50)->nullable()->after('academic_year'); // SQ, Management
            $table->enum('is_eligible', ['YES', 'NO'])->nullable()->after('admission_type');
            $table->string('counsellor_name', 255)->nullable()->after('is_eligible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'city',
                'personal_email',
                'ssc_percentage',
                'hsc_percentage',
                'diploma_percentage',
                'btech_cgpa_upto_5th',
                'admission_type',
                'is_eligible',
                'counsellor_name',
            ]);
        });
    }
};
