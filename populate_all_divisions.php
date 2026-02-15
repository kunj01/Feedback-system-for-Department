<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;

echo "=== Populating Students for All Divisions ===\n\n";

// Student names pool
$firstNames = [
    'Rahul', 'Priya', 'Amit', 'Sneha', 'Vikram', 'Anjali', 'Rohan', 'Pooja',
    'Karan', 'Neha', 'Arjun', 'Divya', 'Aditya', 'Riya', 'Siddharth', 'Kavya',
    'Harsh', 'Ishita', 'Akash', 'Nikita', 'Yash', 'Tanvi', 'Dev', 'Ananya',
    'Varun', 'Meera', 'Nikhil', 'Shruti', 'Kunal', 'Aditi', 'Manav', 'Simran'
];

$lastNames = [
    'Sharma', 'Patel', 'Kumar', 'Desai', 'Singh', 'Gupta', 'Mehta', 'Shah',
    'Joshi', 'Rao', 'Reddy', 'Nair', 'Iyer', 'Pillai', 'Verma', 'Agarwal',
    'Chopra', 'Malhotra', 'Saxena', 'Mishra', 'Pandey', 'Trivedi', 'Kapoor', 'Bhatia'
];

$divisions = Division::with('batches')->orderBy('id')->get();
$departmentId = 1;

$enrollmentCounter = 10; // Start from 24IT010

foreach ($divisions as $division) {
    echo "\n--- Processing Division: {$division->name} (Semester {$division->semester}) ---\n";
    
    // Determine how many students per division based on number of batches
    $batchCount = $division->batches->count();
    $studentsPerBatch = 4; // 4 students per batch
    $totalStudents = $batchCount * $studentsPerBatch;
    
    echo "Batches in division: {$batchCount}\n";
    echo "Students to create: {$totalStudents}\n\n";
    
    $created = 0;
    
    for ($i = 0; $i < $totalStudents; $i++) {
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $enrollmentNo = '24IT' . str_pad($enrollmentCounter, 3, '0', STR_PAD_LEFT);
        
        // Check if enrollment number already exists
        if (Student::where('enrollment_no', $enrollmentNo)->exists()) {
            $enrollmentCounter++;
            continue;
        }
        
        $email = strtolower($firstName) . '.' . strtolower($lastName) . $enrollmentCounter . '@student.com';
        
        // Check if user exists
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'password' => Hash::make('password123'),
                'phone' => '98765' . str_pad($enrollmentCounter, 5, '0', STR_PAD_LEFT),
                'department_id' => $departmentId,
                'is_active' => true,
            ]);
        }
        
        // Create student
        Student::create([
            'user_id' => $user->id,
            'student_id' => 'STU2024' . str_pad($enrollmentCounter, 3, '0', STR_PAD_LEFT),
            'enrollment_no' => $enrollmentNo,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'roll_no' => str_pad($enrollmentCounter, 3, '0', STR_PAD_LEFT),
            'registration_no' => 'REG2024' . str_pad($enrollmentCounter, 3, '0', STR_PAD_LEFT),
            'email' => $email,
            'personal_email' => $email,
            'contact' => '98765' . str_pad($enrollmentCounter, 5, '0', STR_PAD_LEFT),
            'division_id' => $division->id,
            'semester' => $division->semester,
            'department_id' => $departmentId,
            'academic_year' => '2024-2025',
            'gender' => ($i % 2 == 0) ? 'Male' : 'Female',
            'dob' => '2003-' . str_pad(($i % 12) + 1, 2, '0', STR_PAD_LEFT) . '-' . str_pad(($i % 28) + 1, 2, '0', STR_PAD_LEFT),
            'father_name' => 'Father of ' . $firstName,
            'mother_name' => 'Mother of ' . $firstName,
            'address' => '123 Test Street',
            'city' => 'Mumbai',
            'course' => 'B.Tech IT',
            'admission_type' => 'Regular',
            'is_eligible' => 'YES',
            'cgpa' => number_format(7.0 + ($i * 0.1), 2),
            'ssc_percentage' => 75 + ($i % 15),
            'hsc_percentage' => 70 + ($i % 20),
            'training_status' => 'Active',
        ]);
        
        echo "  ✓ {$enrollmentNo} - {$firstName} {$lastName}\n";
        
        $created++;
        $enrollmentCounter++;
    }
    
    echo "Created {$created} students for {$division->name}\n";
}

echo "\n=== Summary ===\n";
foreach ($divisions as $division) {
    $totalStudents = Student::where('division_id', $division->id)->count();
    $assignedStudents = Student::where('division_id', $division->id)->whereNotNull('batch_id')->count();
    $unassignedStudents = Student::where('division_id', $division->id)->whereNull('batch_id')->count();
    
    echo "\n{$division->name}:\n";
    echo "  Total: {$totalStudents} students\n";
    echo "  Assigned: {$assignedStudents}\n";
    echo "  Unassigned: {$unassignedStudents}\n";
    echo "  Batches: " . $division->batches->count() . " (" . $division->batches->pluck('batch_name')->implode(', ') . ")\n";
}

echo "\n✓ All divisions populated successfully!\n";
echo "You can now assign students to batches in each division.\n";
