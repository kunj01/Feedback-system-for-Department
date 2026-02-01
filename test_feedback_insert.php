<?php

// Test script to verify feedback submission
// Run with: php artisan tinker < test_feedback_insert.php

use App\Models\Feedback;
use App\Models\Student;
use App\Models\User;

echo "=== FEEDBACK SYSTEM TEST ===\n\n";

// Check if feedback table exists
try {
    echo "1. Checking feedback table...\n";
    $tableExists = Schema::hasTable('feedback');
    echo "   Feedback table exists: " . ($tableExists ? "✓ YES" : "✗ NO") . "\n";
    
    if ($tableExists) {
        $columns = Schema::getColumnListing('feedback');
        echo "   Columns: " . implode(', ', $columns) . "\n";
    }
} catch (\Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

echo "\n2. Checking existing feedback...\n";
try {
    $count = Feedback::count();
    echo "   Total feedback records: " . $count . "\n";
    
    if ($count > 0) {
        $latest = Feedback::with('student.user')->latest()->first();
        echo "   Latest feedback ID: " . $latest->id . "\n";
        echo "   Student: " . ($latest->student->user->name ?? 'N/A') . "\n";
        echo "   Rating: " . $latest->overall_rating . "/5\n";
    }
} catch (\Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

echo "\n3. Checking students...\n";
try {
    $studentCount = Student::count();
    echo "   Total students: " . $studentCount . "\n";
    
    if ($studentCount > 0) {
        $student = Student::with('user')->first();
        echo "   Sample student ID: " . $student->id . "\n";
        echo "   Sample student name: " . ($student->user->name ?? 'N/A') . "\n";
    } else {
        echo "   ⚠ No students found! Creating test student...\n";
        
        // Create a test student
        $user = User::firstOrCreate(
            ['email' => 'teststudent@test.com'],
            [
                'name' => 'Test Student',
                'password' => bcrypt('password'),
            ]
        );
        
        $student = Student::firstOrCreate(
            ['user_id' => $user->id],
            [
                'student_id' => 'TEST001',
                'department_id' => 1,
            ]
        );
        
        echo "   Created test student: " . $student->id . "\n";
    }
} catch (\Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

echo "\n4. Creating test feedback...\n";
try {
    $student = Student::first();
    
    if (!$student) {
        echo "   ✗ No student available\n";
    } else {
        // Create test feedback
        $feedback = Feedback::create([
            'student_id' => $student->id,
            'subject_id' => 1,
            'faculty_id' => 1,
            'responses' => [
                'q1' => 5,
                'q2' => 5,
                'q3' => 4,
                'q4' => 5,
                'q5' => 5,
                'q6' => 4,
                'q7' => 5,
                'q8' => 4,
            ],
            'overall_rating' => 5,
            'comments' => 'Test feedback created by automated script',
        ]);
        
        echo "   ✓ Created test feedback ID: " . $feedback->id . "\n";
        echo "   Student ID: " . $feedback->student_id . "\n";
        echo "   Subject: " . $feedback->subject_id . "\n";
        echo "   Faculty: " . $feedback->faculty_id . "\n";
        echo "   Rating: " . $feedback->overall_rating . "/5\n";
    }
} catch (\Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n5. Final verification...\n";
try {
    $totalFeedback = Feedback::count();
    echo "   Total feedback now: " . $totalFeedback . "\n";
    
    if ($totalFeedback > 0) {
        echo "   ✓ Feedback system is working!\n";
    } else {
        echo "   ✗ No feedback found\n";
    }
} catch (\Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
