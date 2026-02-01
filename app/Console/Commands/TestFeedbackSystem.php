<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Feedback;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class TestFeedbackSystem extends Command
{
    protected $signature = 'feedback:test';
    protected $description = 'Test and debug the feedback system';

    public function handle()
    {
        $this->info('=== FEEDBACK SYSTEM TEST ===');
        $this->newLine();

        // Check table
        $this->info('1. Checking feedback table...');
        if (Schema::hasTable('feedback')) {
            $this->line('   ✓ Feedback table exists');
            $columns = Schema::getColumnListing('feedback');
            $this->line('   Columns: ' . implode(', ', $columns));
        } else {
            $this->error('   ✗ Feedback table does NOT exist');
            return;
        }

        // Check existing feedback
        $this->newLine();
        $this->info('2. Checking existing feedback...');
        $count = Feedback::count();
        $this->line("   Total feedback records: $count");

        if ($count > 0) {
            $latest = Feedback::with('student.user')->latest()->first();
            $this->line('   Latest feedback ID: ' . $latest->id);
            $this->line('   Student: ' . ($latest->student->user->name ?? 'N/A'));
            $this->line('   Rating: ' . $latest->overall_rating . '/5');
        }

        // Check students
        $this->newLine();
        $this->info('3. Checking students...');
        $studentCount = Student::count();
        $this->line("   Total students: $studentCount");

        if ($studentCount == 0) {
            $this->warn('   Creating test student...');
            $user = User::create([
                'name' => 'Test Student',
                'email' => 'teststudent@' . time() . '.com',
                'password' => bcrypt('password'),
            ]);
            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => 'TEST' . time(),
                'department_id' => 1,
            ]);
            $this->line('   Created student ID: ' . $student->id);
        }

        // Create test feedback
        $this->newLine();
        $this->info('4. Creating test feedback...');
        $student = Student::first();

        if (!$student) {
            $this->error('   No student available');
            return;
        }

        try {
            $feedback = Feedback::create([
                'student_id' => $student->id,
                'subject_id' => 1,
                'faculty_id' => 1,
                'responses' => [
                    'q1' => 5, 'q2' => 5, 'q3' => 4, 'q4' => 5,
                    'q5' => 5, 'q6' => 4, 'q7' => 5, 'q8' => 4,
                ],
                'overall_rating' => 5,
                'comments' => 'Test feedback created at ' . now(),
            ]);

            $this->line('   ✓ Created feedback ID: ' . $feedback->id);
            $this->line('   Student ID: ' . $feedback->student_id);
            $this->line('   Rating: ' . $feedback->overall_rating . '/5');
        } catch (\Exception $e) {
            $this->error('   ERROR: ' . $e->getMessage());
        }

        // Final check
        $this->newLine();
        $this->info('5. Final verification...');
        $totalFeedback = Feedback::count();
        $this->line("   Total feedback now: $totalFeedback");

        if ($totalFeedback > 0) {
            $this->info('   ✓ Feedback system is working!');
        } else {
            $this->error('   ✗ No feedback found');
        }

        $this->newLine();
        $this->info('=== TEST COMPLETE ===');
    }
}

