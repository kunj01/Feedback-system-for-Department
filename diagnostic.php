<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

// Check if admin users exist
echo "=== DIAGNOSTIC REPORT ===" . PHP_EOL . PHP_EOL;

echo "1. Checking Roles..." . PHP_EOL;
$roles = Role::all();
echo "   Total Roles: " . $roles->count() . PHP_EOL;
foreach ($roles as $role) {
    $userCount = User::role($role->name)->count();
    echo "   - {$role->name}: {$userCount} users" . PHP_EOL;
}
echo PHP_EOL;

echo "2. Checking Admin Users..." . PHP_EOL;
$admins = User::role('Admin')->get();
if ($admins->count() > 0) {
    foreach ($admins as $admin) {
        echo "   - {$admin->name} ({$admin->email})" . PHP_EOL;
        echo "     Roles: " . $admin->getRoleNames()->implode(', ') . PHP_EOL;
    }
} else {
    echo "   ⚠️  NO ADMIN USERS FOUND!" . PHP_EOL;
    echo "   Run: php artisan db:seed --class=DefaultAdminSeeder" . PHP_EOL;
}
echo PHP_EOL;

echo "3. Checking Database Connections..." . PHP_EOL;
try {
    $totalUsers = User::count();
    echo "   ✓ Database connected" . PHP_EOL;
    echo "   Total Users: {$totalUsers}" . PHP_EOL;
} catch (\Exception $e) {
    echo "   ✗ Database Error: " . $e->getMessage() . PHP_EOL;
}
echo PHP_EOL;

echo "4. Checking Students..." . PHP_EOL;
$studentCount = \App\Models\Student::count();
echo "   Total Students: {$studentCount}" . PHP_EOL;
echo PHP_EOL;

echo "5. Checking Departments..." . PHP_EOL;
$deptCount = \App\Models\Department::count();
echo "   Total Departments: {$deptCount}" . PHP_EOL;
echo PHP_EOL;

echo "6. Checking Companies..." . PHP_EOL;
$companyCount = \App\Models\Company::count();
echo "   Total Companies: {$companyCount}" . PHP_EOL;
echo PHP_EOL;

echo "7. Checking Projects..." . PHP_EOL;
$projectCount = \App\Models\Project::count();
echo "   Total Projects: {$projectCount}" . PHP_EOL;
echo PHP_EOL;

echo "=== RECOMMENDATIONS ===" . PHP_EOL;
if ($admins->count() === 0) {
    echo "⚠️  Run: php artisan migrate:fresh --seed" . PHP_EOL;
} else {
    echo "✓ System appears ready" . PHP_EOL;
    echo "  Login with:" . PHP_EOL;
    $firstAdmin = $admins->first();
    echo "  Email: {$firstAdmin->email}" . PHP_EOL;
    echo "  Default Password: password" . PHP_EOL;
}
echo PHP_EOL;
