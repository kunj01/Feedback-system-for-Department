<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Division;
use App\Models\Timetable;

$division = Division::where('name', '4-IT-1')->first();

if (!$division) {
    echo "Division 4-IT-1 not found!\n";
    exit;
}

echo "=== 4-IT-1 Timetable - Monday 09:10-10:10 ===\n\n";

$entries = Timetable::where('division_id', $division->id)
    ->where('day', 'Monday')
    ->where('time_slot', '09:10-10:10')
    ->with(['subject', 'faculty', 'batch'])
    ->get();

echo "Found {$entries->count()} entries\n\n";

foreach ($entries as $entry) {
    $batch = $entry->batch ? $entry->batch->batch_name : 'Lecture (no batch)';
    $subject = $entry->subject ? $entry->subject->subject_code : 'NULL';
    $faculty = $entry->faculty ? $entry->faculty->short_code : 'NULL';
    
    echo "  Subject ID: {$entry->subject_id} ({$subject})\n";
    echo "  Faculty ID: {$entry->faculty_id} ({$faculty})\n";
    echo "  Room: '{$entry->room_no}'\n";
    echo "  Batch: {$batch}\n";
    echo "  ---\n";
}

echo "\n=== Tuesday 09:10-10:10 ===\n\n";

$entries = Timetable::where('division_id', $division->id)
    ->where('day', 'Tuesday')
    ->where('time_slot', '09:10-10:10')
    ->with(['subject', 'faculty', 'batch'])
    ->get();

echo "Found {$entries->count()} entries\n\n";

foreach ($entries as $entry) {
    $batch = $entry->batch ? $entry->batch->batch_name : 'Lecture (no batch)';
    $subject = $entry->subject ? $entry->subject->subject_code : 'NULL';
    $faculty = $entry->faculty ? $entry->faculty->short_code : 'NULL';
    
    echo "  Subject: {$subject} | Faculty: {$faculty} | Room: '{$entry->room_no}' | Batch: {$batch}\n";
}
