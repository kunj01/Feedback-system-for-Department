<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use App\Models\User;
use App\Models\Division;

echo "=== Searching for Nikita Reddy in Semester 6 IT Division 2 ===\n\n";

// Search for the student
$students = Student::with('user', 'division')
    ->where('semester', 6)
    ->whereHas('user', function($query) {
        $query->where('name', 'like', '%Nikita%')
              ->where('name', 'like', '%Reddy%');
    })
    ->get();

if ($students->count() > 0) {
    foreach ($students as $student) {
        echo "Found Student:\n";
        echo "Name: " . $student->user->name . "\n";
        echo "Email/Username: " . $student->user->email . "\n";
        echo "Password: password123\n";
        echo "Enrollment No: " . $student->enrollment_no . "\n";
        echo "Division: " . ($student->division ? $student->division->name : 'N/A') . "\n";
        echo "Semester: " . $student->semester . "\n";
        echo "\n---\n\n";
    }
} else {
    echo "No student named 'Nikita Reddy' found in semester 6.\n\n";
    echo "Let me show all Nikita students in semester 6:\n\n";
    
    $nikitaStudents = Student::with('user', 'division')
        ->where('semester', 6)
        ->whereHas('user', function($query) {
            $query->where('name', 'like', '%Nikita%');
        })
        ->get();
    
    if ($nikitaStudents->count() > 0) {
        foreach ($nikitaStudents as $student) {
            echo "Name: " . $student->user->name . "\n";
            echo "Email: " . $student->user->email . "\n";
            echo "Password: password123\n";
            echo "Division: " . ($student->division ? $student->division->name : 'N/A') . "\n";
            echo "\n";
        }
    } else {
        echo "No students named Nikita found in semester 6.\n";
    }
}
