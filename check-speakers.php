<?php

use App\Models\Speaker;

// Check for pending speakers
$pending = Speaker::where('approval_status', 'pending')->get();

echo "=== Pending Speakers ===\n";
if ($pending->count() > 0) {
    foreach ($pending as $speaker) {
        echo "ID: {$speaker->id} | Name: {$speaker->name} | Email: {$speaker->email}\n";
    }
} else {
    echo "No pending speakers found.\n";
}

// Check all speakers
$all = Speaker::all();
echo "\n=== All Speakers ===\n";
foreach ($all as $speaker) {
    echo "ID: {$speaker->id} | {$speaker->name} | Status: {$speaker->approval_status}\n";
}
