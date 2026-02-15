<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Division;
use App\Models\Timetable;

echo "=== Timetable Entries Per Division ===\n\n";

$divisions = Division::all();
foreach ($divisions as $division) {
    $count = Timetable::where('division_id', $division->id)->count();
    echo "{$division->name}: {$count} entries\n";
}

echo "\n=== Total: " . Timetable::count() . " entries ===\n";

echo "\n=== Sample Entries for 4-IT-1 ===\n";
$samples = Timetable::whereHas('division', function($q) {
    $q->where('name', '4-IT-1');
})->with(['subject', 'faculty', 'batch'])
  ->take(10)
  ->get();

foreach ($samples as $entry) {
    $batch = $entry->batch ? " - Batch {$entry->batch->name}" : " - Lecture";
    echo "{$entry->day} {$entry->time_slot}: {$entry->subject->subject_name} ({$entry->faculty->short_code}){$batch}\n";
}
