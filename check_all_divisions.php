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

$divisions = ['4-IT-1', '4-IT-2', '6-IT-1', '6-IT-2'];

foreach ($divisions as $divName) {
    $division = Division::where('name', $divName)->first();
    
    echo "\n=== {$divName} Timetable Grid ===\n\n";
    echo "Time Slot              | Mon | Tue | Wed | Thu | Fri | Sat |\n";
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
    $uniqueFilled = Timetable::where('division_id', $division->id)
        ->selectRaw('DISTINCT day, time_slot')
        ->get()
        ->count();
    
    echo "\nCells with entries: {$uniqueFilled} / {$totalCells}\n";
    echo "Empty cells: " . ($totalCells - $uniqueFilled) . "\n";
    echo "Percentage filled: " . round(($uniqueFilled / $totalCells) * 100, 1) . "%\n";
}
