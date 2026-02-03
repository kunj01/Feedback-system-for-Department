<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use App\Models\User;

echo "=== Finding Orphaned Students ===" . PHP_EOL;

$allStudents = Student::all();
echo "Total students: " . $allStudents->count() . PHP_EOL;

$orphanedStudents = $allStudents->filter(function($student) {
    return $student->user === null;
});

echo "Orphaned students (no user): " . $orphanedStudents->count() . PHP_EOL . PHP_EOL;

if ($orphanedStudents->count() > 0) {
    echo "Orphaned student records:" . PHP_EOL;
    echo "------------------------" . PHP_EOL;
    foreach ($orphanedStudents as $student) {
        echo "Student ID: {$student->id} | User ID: {$student->user_id} | Student Enrollment: {$student->student_id}" . PHP_EOL;
    }
    echo PHP_EOL;
    
    echo "Do you want to delete these orphaned student records? (yes/no): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    
    if (trim($line) == 'yes') {
        $deletedCount = 0;
        foreach ($orphanedStudents as $student) {
            $student->delete();
            $deletedCount++;
        }
        
        echo PHP_EOL . "✓ Successfully deleted {$deletedCount} orphaned student records." . PHP_EOL;
        echo "Total students remaining: " . Student::count() . PHP_EOL;
    } else {
        echo "Deletion cancelled." . PHP_EOL;
    }
} else {
    echo "No orphaned students found. All students have valid user references." . PHP_EOL;
}
