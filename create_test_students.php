<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Creating Test Students for Filter Demo ===\n\n";

$testStudents = [
    ['first_name' => 'Rahul', 'last_name' => 'Sharma', 'enrollment_no' => '24IT002'],
    ['first_name' => 'Priya', 'last_name' => 'Patel', 'enrollment_no' => '24IT003'],
    ['first_name' => 'Amit', 'last_name' => 'Kumar', 'enrollment_no' => '24IT004'],
    ['first_name' => 'Sneha', 'last_name' => 'Desai', 'enrollment_no' => '24IT005'],
    ['first_name' => 'Vikram', 'last_name' => 'Singh', 'enrollment_no' => '24IT006'],
    ['first_name' => 'Anjali', 'last_name' => 'Gupta', 'enrollment_no' => '24IT007'],
    ['first_name' => 'Rohan', 'last_name' => 'Mehta', 'enrollment_no' => '24IT008'],
    ['first_name' => 'Pooja', 'last_name' => 'Shah', 'enrollment_no' => '24IT009'],
];

$divisionId = 1; // 4-IT-1
$departmentId = 1;

foreach ($testStudents as $index => $studentData) {
    $email = strtolower($studentData['first_name']) . '.' . strtolower($studentData['last_name']) . '@student.com';
    
    // Check if enrollment number already exists
    if (Student::where('enrollment_no', $studentData['enrollment_no'])->exists()) {
        echo "⊘ Skipped: " . $studentData['first_name'] . " " . $studentData['last_name'] 
             . " (already exists)\n";
        continue;
    }
    
    // Check if user exists, create if not
    $user = User::where('email', $email)->first();
    
    if (!$user) {
        $user = User::create([
            'name' => $studentData['first_name'] . ' ' . $studentData['last_name'],
            'email' => $email,
            'password' => Hash::make('password123'),
            'phone' => '98765432' . str_pad($index, 2, '0', STR_PAD_LEFT),
            'department_id' => $departmentId,
            'is_active' => true,
        ]);
    }
    
    // Create student
    Student::create([
        'user_id' => $user->id,
        'student_id' => 'STU2024' . str_pad($index + 2, 3, '0', STR_PAD_LEFT),
        'enrollment_no' => $studentData['enrollment_no'],
        'first_name' => $studentData['first_name'],
        'last_name' => $studentData['last_name'],
        'roll_no' => str_pad($index + 2, 3, '0', STR_PAD_LEFT),
        'registration_no' => 'REG2024' . str_pad($index + 2, 3, '0', STR_PAD_LEFT),
        'email' => $email,
        'personal_email' => $email,
        'contact' => '98765432' . str_pad($index, 2, '0', STR_PAD_LEFT),
        'division_id' => $divisionId,
        'semester' => 4,
        'department_id' => $departmentId,
        'academic_year' => '2024-2025',
        'gender' => ($index % 2 == 0) ? 'Male' : 'Female',
        'dob' => '2003-01-' . str_pad($index + 10, 2, '0', STR_PAD_LEFT),
        'father_name' => 'Father of ' . $studentData['first_name'],
        'mother_name' => 'Mother of ' . $studentData['first_name'],
        'address' => '123 Test Street',
        'city' => 'Mumbai',
        'course' => 'B.Tech IT',
        'admission_type' => 'Regular',
        'is_eligible' => 'YES',
        'cgpa' => number_format(7.5 + ($index * 0.2), 2),
        'ssc_percentage' => 80 + $index,
        'hsc_percentage' => 75 + $index,
        'training_status' => 'Active',
    ]);
    
    echo "✓ Created: " . $studentData['first_name'] . " " . $studentData['last_name'] 
         . " (" . $studentData['enrollment_no'] . ")\n";
}

echo "\n=== Summary ===\n";
echo "Total students in 4-IT-1: " . Student::where('division_id', $divisionId)->count() . "\n";
echo "Assigned to batches: " . Student::where('division_id', $divisionId)->whereNotNull('batch_id')->count() . "\n";
echo "Unassigned: " . Student::where('division_id', $divisionId)->whereNull('batch_id')->count() . "\n";
echo "\n✓ Test students created successfully!\n";
echo "Now you can test the filter functionality with multiple students.\n";
