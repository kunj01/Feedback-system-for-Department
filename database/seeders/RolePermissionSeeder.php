<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view users', 'create users', 'edit users', 'delete users',

            // Role Management
            'view roles', 'create roles', 'edit roles', 'delete roles',

            // Student Management
            'view students', 'create students', 'edit students', 'delete students',

            // Department Management
            'view departments', 'create departments', 'edit departments', 'delete departments',

            // Company Management
            'view companies', 'create companies', 'edit companies', 'delete companies',

            // Project Management
            'view projects', 'create projects', 'edit projects', 'delete projects',
            'assign projects', 'update project status',

            // Evaluation Management
            'view evaluations', 'create evaluations', 'edit evaluations', 'delete evaluations',
            'approve evaluations', 'lock evaluations',

            // Placement Management
            'view placements', 'create placements', 'edit placements', 'delete placements',
            'confirm placements',

            // Report Management
            'view reports', 'upload reports', 'review reports',

            // Notification Management
            'view notifications', 'send notifications',

            // System Settings
            'view settings', 'edit settings',

            // Audit Logs
            'view audit logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions

        // Admin Role - Full access
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // T&P Officer Role
        $tnpRole = Role::firstOrCreate(['name' => 'TnP', 'guard_name' => 'web']);
        $tnpRole->syncPermissions([
            'view students', 'edit students',
            'view companies', 'create companies', 'edit companies',
            'view projects', 'create projects', 'edit projects', 'assign projects', 'update project status',
            'view evaluations', 'approve evaluations',
            'view placements', 'create placements', 'edit placements', 'confirm placements',
            'view reports', 'review reports',
            'view notifications', 'send notifications',
        ]);

        // Head (HOD) Role
        $headRole = Role::firstOrCreate(['name' => 'Head', 'guard_name' => 'web']);
        $headRole->syncPermissions([
            'view students', 'view evaluations', 'approve evaluations',
            'view projects', 'view placements', 'view reports',
            'view notifications',
            // Head can also act as Guide
            'create evaluations', 'edit evaluations',
        ]);

        // Guide Role
        $guideRole = Role::firstOrCreate(['name' => 'Guide', 'guard_name' => 'web']);
        $guideRole->syncPermissions([
            'view students', 'view projects',
            'view evaluations', 'create evaluations', 'edit evaluations', 'lock evaluations',
            'view reports', 'review reports',
            'view notifications',
        ]);

        // Student Role
        $studentRole = Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);
        $studentRole->syncPermissions([
            'view projects', 'view evaluations', 'view placements',
            'upload reports', 'view notifications',
        ]);

        // Faculty Role
        $facultyRole = Role::firstOrCreate(['name' => 'Faculty', 'guard_name' => 'web']);
        $facultyRole->syncPermissions([
            'view students', 'view projects',
            'view evaluations', 'create evaluations', 'edit evaluations',
            'view reports', 'review reports',
            'view notifications',
        ]);
    }
}

