<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\FormResponse;
use App\Models\FormAssignment;

echo "=== FORM SUBMISSION SYSTEM CHECK ===\n\n";

// Check form_responses table
echo "1. Checking form_responses table...\n";
if (Schema::hasTable('form_responses')) {
    echo "   ✓ Table exists\n";
    $columns = DB::select('PRAGMA table_info(form_responses)');
    echo "   Columns: " . implode(', ', array_column($columns, 'name')) . "\n";
    $count = FormResponse::count();
    echo "   Total records: {$count}\n\n";
} else {
    echo "   ✗ Table MISSING!\n";
    echo "   → Run: php artisan migrate\n\n";
}

// Check form_assignments table
echo "2. Checking form_assignments table...\n";
if (Schema::hasTable('form_assignments')) {
    echo "   ✓ Table exists\n";
    $count = FormAssignment::count();
    echo "   Total assignments: {$count}\n";
    if ($count > 0) {
        $pending = FormAssignment::where('status', 'pending')->count();
        $completed = FormAssignment::where('status', 'completed')->count();
        echo "   Pending: {$pending}, Completed: {$completed}\n";
    }
    echo "\n";
} else {
    echo "   ✗ Table MISSING!\n\n";
}

// Check recent submissions
echo "3. Checking recent form submissions...\n";
if (Schema::hasTable('form_responses')) {
    $recent = FormResponse::latest()->take(5)->get();
    if ($recent->count() > 0) {
        echo "   Recent submissions:\n";
        foreach ($recent as $response) {
            echo "     - ID: {$response->id}, Student: {$response->student_id}, Submitted: {$response->created_at->diffForHumans()}\n";
        }
    } else {
        echo "   ⚠ No submissions found\n";
    }
} else {
    echo "   ✗ Cannot check - table missing\n";
}

echo "\n=== CHECK COMPLETE ===\n";
