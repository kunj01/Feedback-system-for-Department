<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Student;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "╔════════════════════════════════════════════════════════════╗" . PHP_EOL;
echo "║           DEMO SETUP - CREATE ADMIN & STUDENTS             ║" . PHP_EOL;
echo "╚════════════════════════════════════════════════════════════╝" . PHP_EOL;
echo PHP_EOL;

DB::beginTransaction();

try {
    // Get or create default department
    $department = Department::first();
    if (!$department) {
        $department = Department::create([
            'name' => 'Information Technology',
            'code' => 'IT',
            'is_active' => true
        ]);
        echo "✓ Created default department: IT" . PHP_EOL;
    }

    // Ensure roles exist
    $adminRole = Role::firstOrCreate(['name' => 'Admin']);
    $studentRole = Role::firstOrCreate(['name' => 'Student']);
    echo "✓ Roles verified: Admin & Student" . PHP_EOL . PHP_EOL;

    // ==================== CREATE ADMIN ====================
    echo "Creating Admin User..." . PHP_EOL;
    echo "------------------------" . PHP_EOL;
    
    $adminEmail = 'admin@system.com';
    $adminPassword = 'admin123';
    
    // Delete existing admin if exists (including soft-deleted)
    $existingAdmin = User::withTrashed()->where('email', $adminEmail)->first();
    if ($existingAdmin) {
        $existingAdmin->forceDelete();
        echo "  Removed existing admin user" . PHP_EOL;
    }
    
    $admin = User::create([
        'name' => 'System Administrator',
        'email' => $adminEmail,
        'password' => Hash::make($adminPassword),
        'department_id' => $department->id,
        'is_active' => true,
    ]);
    
    $admin->assignRole('Admin');
    
    echo "  ✓ Admin Created" . PHP_EOL;
    echo "    Email: {$adminEmail}" . PHP_EOL;
    echo "    Password: {$adminPassword}" . PHP_EOL;
    echo PHP_EOL;

    // ==================== CREATE STUDENTS ====================
    echo "Creating Student Users..." . PHP_EOL;
    echo "------------------------" . PHP_EOL;
    
    $studentsCreated = 0;
    
    // Range 1: 23IT028 to 23IT047 (20 students)
    for ($i = 24; $i <= 47; $i++) {
        $enrollmentId = '23IT' . str_pad($i, 3, '0', STR_PAD_LEFT);
        $email = strtolower($enrollmentId) . '@charusat.edu.in';
        $password = strtolower($enrollmentId);
        $name = strtoupper($enrollmentId);
        
        // Delete existing user if exists (including soft-deleted)
        $existingUser = User::withTrashed()->where('email', $email)->first();
        if ($existingUser) {
            $existingUser->forceDelete();
        }
        
        // Delete existing student record if exists
        $existingStudent = Student::where('student_id', $enrollmentId)->first();
        if ($existingStudent) {
            $existingStudent->delete();
        }
        
        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'department_id' => $department->id,
            'is_active' => true,
        ]);
        
        // Assign Student role
        $user->assignRole('Student');
        
        // Create student record
        Student::create([
            'user_id' => $user->id,
            'student_id' => $enrollmentId,
            'enrollment_year' => 2023,
            'semester' => 6,
            'is_active' => true,
        ]);
        
        $studentsCreated++;
        echo "  ✓ {$name} - {$email} (password: {$password})" . PHP_EOL;
    }
    
    // Range 2: D24IT142 to D24IT156 (15 students)
    for ($i = 142; $i <= 156; $i++) {
        $enrollmentId = 'D24IT' . $i;
        $email = strtolower($enrollmentId) . '@charusat.edu.in';
        $password = strtolower($enrollmentId);
        $name = strtoupper($enrollmentId);
        
        // Delete existing user if exists (including soft-deleted)
        $existingUser = User::withTrashed()->where('email', $email)->first();
        if ($existingUser) {
            $existingUser->forceDelete();
        }
        
        // Delete existing student record if exists
        $existingStudent = Student::where('student_id', $enrollmentId)->first();
        if ($existingStudent) {
            $existingStudent->delete();
        }
        
        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'department_id' => $department->id,
            'is_active' => true,
        ]);
        
        // Assign Student role
        $user->assignRole('Student');
        
        // Create student record
        Student::create([
            'user_id' => $user->id,
            'student_id' => $enrollmentId,
            'enrollment_year' => 2024,
            'semester' => 4,
            'is_active' => true,
        ]);
        
        $studentsCreated++;
        echo "  ✓ {$name} - {$email} (password: {$password})" . PHP_EOL;
    }
    
    DB::commit();
    
    echo PHP_EOL;
    echo "╔════════════════════════════════════════════════════════════╗" . PHP_EOL;
    echo "║                    SETUP COMPLETED!                        ║" . PHP_EOL;
    echo "╚════════════════════════════════════════════════════════════╝" . PHP_EOL;
    echo PHP_EOL;
    echo "Summary:" . PHP_EOL;
    echo "  • 1 Admin created" . PHP_EOL;
    echo "  • {$studentsCreated} Students created" . PHP_EOL;
    echo "  • Total users: " . User::count() . PHP_EOL;
    echo "  • Total students: " . Student::count() . PHP_EOL;
    echo PHP_EOL;
    echo "Admin Credentials:" . PHP_EOL;
    echo "  Email: {$adminEmail}" . PHP_EOL;
    echo "  Password: {$adminPassword}" . PHP_EOL;
    echo PHP_EOL;
    echo "Student Login Pattern:" . PHP_EOL;
    echo "  Email: [enrollment]@charusat.edu.in" . PHP_EOL;
    echo "  Password: [enrollment] (lowercase)" . PHP_EOL;
    echo "  Example: 23it028@charusat.edu.in / 23it028" . PHP_EOL;
    echo PHP_EOL;
    echo "✓ All users are active and visible in Student Management" . PHP_EOL;
    echo PHP_EOL;

} catch (\Exception $e) {
    DB::rollBack();
    echo PHP_EOL;
    echo "✗ Error: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . $e->getTraceAsString() . PHP_EOL;
    exit(1);
}
