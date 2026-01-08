<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Create Student role
$studentRole = Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);
echo "✓ Student role created/verified\n";

// Create basic permissions if they don't exist
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
        echo "✓ Assigned permission: {$permissionName}\n";
    }
}

echo "\n✓ All done! Student role is ready.\n";
