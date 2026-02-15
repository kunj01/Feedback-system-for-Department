<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Timetable;

echo "=== Checking for NULL values ===\n\n";

$nullSubjects = Timetable::whereNull('subject_id')->count();
$nullFaculty = Timetable::whereNull('faculty_id')->count();
$nullRoom = Timetable::whereNull('room_no')->count();

echo "NULL subject_id: {$nullSubjects}\n";
echo "NULL faculty_id: {$nullFaculty}\n";
echo "NULL room_no: {$nullRoom}\n\n";

echo "=== Checking for missing relationships ===\n\n";

$entries = Timetable::with(['subject', 'faculty'])->get();
$missingSubject = 0;
$missingFaculty = 0;

foreach ($entries as $entry) {
    if (!$entry->subject) {
        $missingSubject++;
        echo "Entry ID {$entry->id}: Missing subject (subject_id={$entry->subject_id})\n";
    }
    if (!$entry->faculty) {
        $missingFaculty++;
        echo "Entry ID {$entry->id}: Missing faculty (faculty_id={$entry->faculty_id})\n";
    }
}

echo "\nTotal entries with missing subject: {$missingSubject}\n";
echo "Total entries with missing faculty: {$missingFaculty}\n";
