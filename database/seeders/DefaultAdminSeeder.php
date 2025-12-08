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
    }
}

