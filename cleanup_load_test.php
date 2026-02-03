<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Student;
use App\Models\FormAssignment;
use App\Models\FormResponse;
use Illuminate\Support\Facades\DB;

echo "=== Cleaning Up Load Test Data ===" . PHP_EOL;
echo PHP_EOL;

DB::beginTransaction();

try {
    // Delete form responses for load test students
    $responsesDeleted = FormResponse::whereHas('student', function($q) {
        $q->where('student_id', 'LIKE', 'LOAD%');
    })->delete();
    
    echo "✓ Deleted {$responsesDeleted} form responses" . PHP_EOL;
    
    // Delete form assignments for load test students
    $assignmentsDeleted = FormAssignment::whereHas('student', function($q) {
        $q->where('student_id', 'LIKE', 'LOAD%');
    })->delete();
    
    echo "✓ Deleted {$assignmentsDeleted} form assignments" . PHP_EOL;
    
    // Delete load test students
    $students = Student::where('student_id', 'LIKE', 'LOAD%')->get();
    $studentCount = $students->count();
    
    foreach ($students as $student) {
        if ($student->user) {
            $student->user->forceDelete();
        }
        $student->delete();
    }
    
    echo "✓ Deleted {$studentCount} load test students" . PHP_EOL;
    
    // Clean up any orphaned users
    $orphanedUsers = User::withTrashed()->where('email', 'LIKE', '%@loadtest.local')->get();
    foreach ($orphanedUsers as $user) {
        $user->forceDelete();
    }
    
    echo "✓ Cleaned up orphaned users" . PHP_EOL;
    
    DB::commit();
    
    echo PHP_EOL;
    echo "✓ Cleanup completed successfully!" . PHP_EOL;
    echo PHP_EOL;

} catch (\Exception $e) {
    DB::rollBack();
    echo PHP_EOL;
    echo "✗ Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
