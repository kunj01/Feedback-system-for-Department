<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@system.com',
            'password' => Hash::make('admin123'),
            'phone' => '1234567890',
            'is_active' => true,
        ]);

        $admin->assignRole('Admin');

        // Create T&P Officer
        $tnp = User::create([
            'name' => 'T&P Officer',
            'email' => 'tnp@system.com',
            'password' => Hash::make('tnp123'),
            'phone' => '1234567891',
            'is_active' => true,
        ]);

        $tnp->assignRole('TnP');

        // Create HOD
        $hod = User::create([
            'name' => 'Head of Department',
            'email' => 'hod@system.com',
            'password' => Hash::make('hod123'),
            'phone' => '1234567892',
            'department_id' => 1, // CSE department
            'is_active' => true,
        ]);

        $hod->assignRole('Head');

        // Create Guide
        $guide = User::create([
            'name' => 'Faculty Guide',
            'email' => 'guide@system.com',
            'password' => Hash::make('guide123'),
            'phone' => '1234567893',
            'department_id' => 1, // CSE department
            'is_active' => true,
        ]);

        $guide->assignRole('Guide');

        // Create Faculty
        $faculty = User::create([
            'name' => 'Faculty Member',
            'email' => 'faculty@system.com',
            'password' => Hash::make('faculty123'),
            'phone' => '1234567895',
            'department_id' => 1, // CSE department
            'is_active' => true,
        ]);

        $faculty->assignRole('Faculty');

        // Create Student
        $student = User::create([
            'name' => 'Demo Student',
            'email' => 'student@system.com',
            'password' => Hash::make('student123'),
            'phone' => '1234567894',
            'department_id' => 1, // CSE department
            'is_active' => true,
        ]);

        $student->assignRole('Student');

        // Create Student Profile
        \App\Models\Student::create([
            'user_id' => $student->id,
            'student_id' => 'STU2024001',
            'first_name' => 'Demo',
            'middle_name' => null,
            'last_name' => 'Student',
            'roll_no' => '001',
            'registration_no' => 'REG2024001',
            'dob' => '2003-01-15',
            'gender' => 'Male',
            'father_name' => 'Father Name',
            'mother_name' => 'Mother Name',
            'address' => '123 Street Name',
            'city' => 'Mumbai',
            'contact' => '1234567894',
            'email' => 'student@system.com',
            'personal_email' => 'demo.student@gmail.com',
            'department_id' => 1,
            'course' => 'B.Tech CSE',
            'batch' => 2024,
            'academic_year' => '2024-2025',
            'cgpa' => 8.5,
            'ssc_percentage' => 85.0,
            'hsc_percentage' => 82.0,
            'admission_type' => 'Regular',
            'is_eligible' => 'YES',
            'training_status' => 'Active',
        ]);
    }
}

