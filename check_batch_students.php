<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use App\Models\Batch;

echo "=== Student-Batch Assignment Check ===\n\n";

echo "Total students: " . Student::count() . "\n";
echo "Students with batch assigned: " . Student::whereNotNull('batch_id')->count() . "\n";
echo "Students without batch: " . Student::whereNull('batch_id')->count() . "\n\n";

echo "=== Students per Batch ===\n";
$batches = Batch::with('division')->orderBy('division_id')->orderBy('batch_name')->get();

foreach($batches as $batch) {
    $studentCount = $batch->students()->count();
    echo $batch->division->name . " - " . $batch->batch_name . ": " . $studentCount . " students\n";
}

echo "\n=== Sample Students (first 5) ===\n";
$students = Student::with(['division', 'batch'])->limit(5)->get();
foreach($students as $student) {
    echo $student->enrollment_no . " - " . $student->first_name . " " . $student->last_name;
    echo " | Division: " . ($student->division->name ?? 'NULL');
    echo " | Batch: " . ($student->batchGroup->batch_name ?? 'NULL') . "\n";
}
