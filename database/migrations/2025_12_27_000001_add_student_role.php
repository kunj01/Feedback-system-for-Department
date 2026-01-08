<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create Student role if it doesn't exist
        $studentRole = Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);
        
        // Assign basic permissions to Student role
        $permissions = [
            'view projects',
            'view evaluations',
            'view placements',
            'upload reports',
            'view notifications',
        ];
        
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            if (!$studentRole->hasPermissionTo($permission)) {
                $studentRole->givePermissionTo($permission);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $role = Role::findByName('Student', 'web');
        $role->delete();
    }
};
