<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Division;
use App\Models\Timetable;

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$timeSlots = [
    '09:10-10:10',
    '10:10-11:10',
    '11:10-12:10',
    '12:10-01:10',
    '01:10-02:10',
    '02:20-03:20',
    '03:20-04:20',
];

$division = Division::where('name', '4-IT-1')->first();

echo "=== 4-IT-1 Timetable Grid ===\n\n";
echo "Time Slot" . str_repeat(' ', 14) . "| " . implode(' | ', array_map(fn($d) => substr($d, 0, 3), $days)) . " |\n";
echo str_repeat('-', 80) . "\n";

foreach ($timeSlots as $slot) {
    echo str_pad($slot, 22) . "| ";
    foreach ($days as $day) {
        $count = Timetable::where('division_id', $division->id)
            ->where('day', $day)
            ->where('time_slot', $slot)
            ->count();
        echo str_pad($count > 0 ? "✓" : "-", 4) . "| ";
    }
    echo "\n";
}

$totalCells = count($timeSlots) * count($days);
$filledCells = Timetable::where('division_id', $division->id)->count();
$uniqueFilled = Timetable::where('division_id', $division->id)
    ->selectRaw('DISTINCT day, time_slot')
    ->get()
    ->count();

echo "\nTotal cells: {$totalCells}\n";
echo "Cells with entries (unique day+time): {$uniqueFilled}\n";
echo "Empty cells: " . ($totalCells - $uniqueFilled) . "\n";
echo "Percentage filled: " . round(($uniqueFilled / $totalCells) * 100, 1) . "%\n";
