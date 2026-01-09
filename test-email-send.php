<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Speaker;
use App\Mail\SpeakerApprovalMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

try {
    // Get the speaker
    $speaker = Speaker::first();
    
    if (!$speaker) {
        echo "✗ No speaker found in database\n";
        exit(1);
    }
    
    echo "Found speaker: {$speaker->name} ({$speaker->email})\n";
    echo "Current feedback_token: " . ($speaker->feedback_token ?? 'null') . "\n\n";
    
    // Generate a new feedback token if missing
    if (!$speaker->feedback_token) {
        echo "Generating new feedback token...\n";
        $speaker->feedback_token = Str::random(64);
        $speaker->save();
        echo "Token generated and saved: {$speaker->feedback_token}\n\n";
    }
    
    // Generate the feedback URL
    $feedbackUrl = route('speaker.feedback.show', ['token' => $speaker->feedback_token]);
    echo "Feedback URL: {$feedbackUrl}\n\n";
    
    // Try sending email
    echo "Attempting to send email...\n";
    Mail::to($speaker->email)->send(new SpeakerApprovalMail($speaker, $feedbackUrl));
    
    echo "✓ Email sent successfully to: {$speaker->email}\n";
    echo "\nPlease check {$speaker->email} for the approval email.\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
