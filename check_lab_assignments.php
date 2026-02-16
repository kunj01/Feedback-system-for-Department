<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\FormAssignment;
use App\Models\Student;

echo "=== Checking Lab Assignment Status ===\n\n";

// Check for Nikita Reddy's assignments
$student = Student::with('user')
    ->whereHas('user', function($query) {
        $query->where('email', 'nikita.reddy66@student.com');
    })
    ->first();

if (!$student) {
    echo "Student Nikita Reddy not found!\n";
    exit(1);
}

echo "Student: " . $student->user->name . "\n";
echo "Email: " . $student->user->email . "\n";
echo "Enrollment: " . $student->enrollment_no . "\n\n";

// Get all form assignments
$assignments = FormAssignment::where('student_id', $student->id)
    ->with(['teacher', 'subject'])
    ->get();

if ($assignments->isEmpty()) {
    echo "No assignments found for this student.\n";
} else {
    echo "Total Assignments: " . $assignments->count() . "\n\n";
    
    echo "Assignment Details:\n";
    echo str_repeat("-", 80) . "\n";
    
    foreach ($assignments as $assignment) {
        echo "Form: " . $assignment->form_title . "\n";
        echo "Teacher: " . ($assignment->teacher ? $assignment->teacher->name : 'N/A') . "\n";
        echo "Subject: " . ($assignment->subject ? $assignment->subject->name : 'N/A') . "\n";
        echo "Type: " . ($assignment->is_lab ? '🔬 LAB' : '📚 LECTURE') . "\n";
        echo "Status: " . strtoupper($assignment->status) . "\n";
        echo "Is Multi-Teacher: " . ($assignment->is_multi_teacher ? 'Yes' : 'No') . "\n";
        echo "Created: " . $assignment->created_at->format('Y-m-d H:i:s') . "\n";
        echo str_repeat("-", 80) . "\n";
    }
    
    // Count by type
    $labCount = $assignments->where('is_lab', true)->count();
    $lectureCount = $assignments->where('is_lab', false)->count();
    
    echo "\nSummary:\n";
    echo "Lab Assignments: " . $labCount . "\n";
    echo "Lecture Assignments: " . $lectureCount . "\n";
}

// Check all lab assignments in the system
echo "\n\n=== All Lab Assignments in System ===\n\n";
$allLabAssignments = FormAssignment::where('is_lab', true)
    ->with(['student.user', 'teacher', 'subject'])
    ->get();

if ($allLabAssignments->isEmpty()) {
    echo "⚠️ WARNING: No lab assignments found in the entire system!\n";
    echo "This indicates that lab assignments are not being created properly.\n\n";
    echo "Common Issues:\n";
    echo "1. Batch pivot data might not have 'type' set to 'lab'\n";
    echo "2. Teachers might not be assigned to lab batches\n";
    echo "3. The assignment creation logic might not be preserving the is_lab flag\n";
} else {
    echo "Total Lab Assignments: " . $allLabAssignments->count() . "\n\n";
    
    foreach ($allLabAssignments->take(5) as $assignment) {
        echo "Student: " . $assignment->student->user->name . "\n";
        echo "Teacher: " . ($assignment->teacher ? $assignment->teacher->name : 'N/A') . "\n";
        echo "Subject: " . ($assignment->subject ? $assignment->subject->name : 'N/A') . "\n";
        echo "Form: " . $assignment->form_title . "\n";
        echo str_repeat("-", 50) . "\n";
    }
}

echo "\n✅ Check Complete!\n";
