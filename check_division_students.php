<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Division;
use App\Models\Student;

echo "=== Current Student Distribution ===\n\n";

$divisions = Division::with(['students', 'batches'])->orderBy('id')->get();

foreach ($divisions as $division) {
    $total = $division->students->count();
    $assigned = $division->students->whereNotNull('batch_id')->count();
    $unassigned = $division->students->whereNull('batch_id')->count();
    $batches = $division->batches->count();
    
    echo "{$division->name} (Semester {$division->semester}):\n";
    echo "  Total Students: {$total}\n";
    echo "  Assigned: {$assigned}\n";
    echo "  Unassigned: {$unassigned}\n";
    echo "  Batches: {$batches} (" . $division->batches->pluck('batch_name')->implode(', ') . ")\n";
    echo "\n";
}

echo "✓ Check complete!\n";
