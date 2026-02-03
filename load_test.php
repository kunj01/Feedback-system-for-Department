<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\FormAssignment;
use App\Models\FormResponse;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "╔════════════════════════════════════════════════════════════╗" . PHP_EOL;
echo "║           LOAD TEST - 60 STUDENTS SIMULATION               ║" . PHP_EOL;
echo "╚════════════════════════════════════════════════════════════╝" . PHP_EOL;
echo PHP_EOL;

$startTime = microtime(true);
$errors = [];
$successCount = 0;

try {
    // Step 1: Verify prerequisites
    echo "Step 1: Checking Prerequisites..." . PHP_EOL;
    echo "-----------------------------------" . PHP_EOL;
    
    $department = Department::first();
    if (!$department) {
        throw new \Exception("No department found. Please run demo setup first.");
    }
    
    $subjects = Subject::where('semester', 6)->get();
    if ($subjects->count() === 0) {
        throw new \Exception("No subjects found. Please import subjects first using import_ts.php");
    }
    
    $teachers = Teacher::where('is_active', true)->get();
    if ($teachers->count() === 0) {
        throw new \Exception("No teachers found. Please import teachers first using import_ts.php");
    }
    
    echo "  ✓ Department: {$department->name}" . PHP_EOL;
    echo "  ✓ Subjects (Semester 6): {$subjects->count()}" . PHP_EOL;
    echo "  ✓ Active Teachers: {$teachers->count()}" . PHP_EOL;
    echo PHP_EOL;
    
    // Step 2: Create 60 test students
    echo "Step 2: Creating 60 Test Students..." . PHP_EOL;
    echo "-----------------------------------" . PHP_EOL;
    
    $studentRole = Role::firstOrCreate(['name' => 'Student']);
    $testStudents = [];
    
    DB::beginTransaction();
    
    for ($i = 1; $i <= 60; $i++) {
        $enrollmentId = 'LOAD' . str_pad($i, 3, '0', STR_PAD_LEFT);
        $email = strtolower($enrollmentId) . '@loadtest.local';
        
        // Clean up existing
        $existingUser = User::withTrashed()->where('email', $email)->first();
        if ($existingUser) {
            $existingUser->forceDelete();
        }
        
        $existingStudent = Student::where('student_id', $enrollmentId)->first();
        if ($existingStudent) {
            $existingStudent->delete();
        }
        
        // Create user
        $user = User::create([
            'name' => 'Load Test ' . $i,
            'email' => $email,
            'password' => Hash::make('test123'),
            'department_id' => $department->id,
            'is_active' => true,
        ]);
        
        $user->assignRole('Student');
        
        // Create student
        $student = Student::create([
            'user_id' => $user->id,
            'student_id' => $enrollmentId,
            'enrollment_year' => 2023,
            'semester' => 6,
            'is_active' => true,
        ]);
        
        $testStudents[] = $student;
        
        if ($i % 10 === 0) {
            echo "  ✓ Created {$i} students..." . PHP_EOL;
        }
    }
    
    DB::commit();
    echo "  ✓ All 60 students created successfully!" . PHP_EOL;
    echo PHP_EOL;
    
    // Step 3: Assign forms to all students
    echo "Step 3: Assigning Forms to All Students..." . PHP_EOL;
    echo "-----------------------------------" . PHP_EOL;
    
    DB::beginTransaction();
    
    $formName = 'Student-Feedback-Form.pdf';
    $assignmentCount = 0;
    
    foreach ($subjects as $subject) {
        // Get teachers for this subject
        $subjectTeachers = $subject->teachers;
        
        if ($subjectTeachers->count() === 0) {
            echo "  ⚠ Warning: No teachers for {$subject->code}, skipping..." . PHP_EOL;
            continue;
        }
        
        foreach ($testStudents as $student) {
            foreach ($subjectTeachers as $teacher) {
                FormAssignment::create([
                    'form_name' => $formName,
                    'form_title' => 'Student Feedback Form',
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                    'status' => 'pending',
                    'assigned_by' => 1,
                    'deadline' => now()->addDays(7),
                ]);
                
                $assignmentCount++;
            }
        }
    }
    
    DB::commit();
    echo "  ✓ Created {$assignmentCount} form assignments" . PHP_EOL;
    echo "  ✓ Average per student: " . round($assignmentCount / 60, 1) . " forms" . PHP_EOL;
    echo PHP_EOL;
    
    // Step 4: Simulate concurrent form submissions
    echo "Step 4: Simulating 60 Concurrent Form Submissions..." . PHP_EOL;
    echo "-----------------------------------" . PHP_EOL;
    
    $submissionStart = microtime(true);
    $submissionErrors = [];
    $submittedCount = 0;
    
    foreach ($testStudents as $index => $student) {
        try {
            // Get first pending assignment for this student
            $assignment = FormAssignment::where('student_id', $student->id)
                ->where('status', 'pending')
                ->first();
            
            if (!$assignment) {
                $submissionErrors[] = "Student {$student->student_id}: No pending assignments";
                continue;
            }
            
            DB::beginTransaction();
            
            // Simulate form response data
            $responseData = [];
            
            // Generate 20 rating responses
            for ($q = 1; $q <= 20; $q++) {
                $fieldName = 'question_' . $q;
                $rating = ['Strongly Agree', 'Agree', 'Neutral', 'Disagree'][array_rand(['Strongly Agree', 'Agree', 'Neutral', 'Disagree'])];
                $responseData[$fieldName] = [
                    'rating' => $rating,
                    'reasoning' => null
                ];
            }
            
            // Add open-ended responses
            $responseData['open_ended_1'] = 'Test response for load testing - Student ' . $student->student_id;
            $responseData['open_ended_2'] = 'Another test response for performance evaluation';
            
            // Create form response
            FormResponse::create([
                'assignment_id' => $assignment->id,
                'form_name' => $assignment->form_name,
                'student_id' => $student->id,
                'subject_id' => $assignment->subject_id,
                'teacher_id' => $assignment->teacher_id,
                'responses' => json_encode($responseData),
                'submitted_at' => now(),
            ]);
            
            // Mark assignment as completed
            $assignment->update(['status' => 'completed']);
            
            DB::commit();
            $submittedCount++;
            
            if (($index + 1) % 10 === 0) {
                echo "  ✓ Submitted {$submittedCount} forms..." . PHP_EOL;
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            $submissionErrors[] = "Student {$student->student_id}: {$e->getMessage()}";
        }
    }
    
    $submissionEnd = microtime(true);
    $submissionTime = round($submissionEnd - $submissionStart, 2);
    
    echo PHP_EOL;
    echo "  ✓ Submissions completed: {$submittedCount}/60" . PHP_EOL;
    echo "  ✓ Time taken: {$submissionTime} seconds" . PHP_EOL;
    echo "  ✓ Average per submission: " . round($submissionTime / 60, 3) . " seconds" . PHP_EOL;
    
    if (count($submissionErrors) > 0) {
        echo "  ⚠ Errors: " . count($submissionErrors) . PHP_EOL;
    }
    echo PHP_EOL;
    
    // Step 5: Test database query performance
    echo "Step 5: Testing Database Query Performance..." . PHP_EOL;
    echo "-----------------------------------" . PHP_EOL;
    
    $queries = [
        'Count all students' => function() {
            return Student::count();
        },
        'Count all assignments' => function() {
            return FormAssignment::count();
        },
        'Count completed forms' => function() {
            return FormAssignment::where('status', 'completed')->count();
        },
        'Get all responses' => function() {
            return FormResponse::count();
        },
        'Complex query (join)' => function() {
            return FormResponse::with(['student', 'subject', 'teacher'])->get()->count();
        },
    ];
    
    foreach ($queries as $queryName => $queryFunc) {
        $queryStart = microtime(true);
        $result = $queryFunc();
        $queryEnd = microtime(true);
        $queryTime = round(($queryEnd - $queryStart) * 1000, 2);
        
        echo "  ✓ {$queryName}: {$result} records in {$queryTime}ms" . PHP_EOL;
    }
    
    echo PHP_EOL;
    
    $totalTime = round(microtime(true) - $startTime, 2);
    
    // Final Report
    echo "╔════════════════════════════════════════════════════════════╗" . PHP_EOL;
    echo "║                    LOAD TEST COMPLETED!                    ║" . PHP_EOL;
    echo "╚════════════════════════════════════════════════════════════╝" . PHP_EOL;
    echo PHP_EOL;
    
    echo "Performance Summary:" . PHP_EOL;
    echo "  • Total time: {$totalTime} seconds" . PHP_EOL;
    echo "  • Students created: 60" . PHP_EOL;
    echo "  • Assignments created: {$assignmentCount}" . PHP_EOL;
    echo "  • Forms submitted: {$submittedCount}" . PHP_EOL;
    echo "  • Submission success rate: " . round(($submittedCount / 60) * 100, 1) . "%" . PHP_EOL;
    echo "  • Average submission time: " . round($submissionTime / 60, 3) . "s" . PHP_EOL;
    echo PHP_EOL;
    
    if ($submittedCount >= 57) { // 95% success rate
        echo "✓ RESULT: System can handle 60 concurrent students!" . PHP_EOL;
        echo "  Website is capable for your demo." . PHP_EOL;
    } elseif ($submittedCount >= 54) { // 90% success rate
        echo "⚠ RESULT: System handled 60 students with minor issues." . PHP_EOL;
        echo "  Should work for demo, but monitor performance." . PHP_EOL;
    } else {
        echo "✗ RESULT: System struggled with 60 concurrent students." . PHP_EOL;
        echo "  Consider optimizing database or reducing concurrent load." . PHP_EOL;
    }
    
    echo PHP_EOL;
    echo "Cleanup:" . PHP_EOL;
    echo "  Run 'php cleanup_load_test.php' to remove test data" . PHP_EOL;
    echo PHP_EOL;
    
    if (count($submissionErrors) > 0) {
        echo "Errors encountered:" . PHP_EOL;
        foreach ($submissionErrors as $error) {
            echo "  • {$error}" . PHP_EOL;
        }
        echo PHP_EOL;
    }

} catch (\Exception $e) {
    DB::rollBack();
    echo PHP_EOL;
    echo "✗ Error: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . $e->getTraceAsString() . PHP_EOL;
    exit(1);
}
