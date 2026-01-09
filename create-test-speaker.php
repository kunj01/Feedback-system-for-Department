<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Speaker;

try {
    // Create a new pending speaker for testing
    $speaker = Speaker::create([
        'name' => 'Test Speaker ' . date('His'),
        'email' => 'kunj5706@gmail.com',  // Your email for testing
        'venue' => 'Test Venue',
        'department' => 'IT',
        'date' => now()->addDays(7),
        'time' => now()->addDays(7)->setTime(14, 0),
        'created_by' => 1,
        'approval_status' => 'pending',
    ]);
    
    echo "✓ New pending speaker created successfully!\n";
    echo "ID: {$speaker->id}\n";
    echo "Name: {$speaker->name}\n";
    echo "Email: {$speaker->email}\n";
    echo "Status: {$speaker->approval_status}\n\n";
    echo "Now go to the website and approve this speaker from the admin panel.\n";
    echo "You should see a success message and receive an email.\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
