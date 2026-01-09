<?php

use Illuminate\Support\Facades\Mail;

// Test email sending
try {
    Mail::raw('This is a test email from Laravel', function ($message) {
        $message->to('23it028@charusat.edu.in')
                ->subject('Test Email - Laravel App');
    });
    
    echo "✅ Test email sent successfully!\n";
    echo "Check your inbox at: 23it028@charusat.edu.in\n";
} catch (Exception $e) {
    echo "❌ Error sending email:\n";
    echo $e->getMessage() . "\n";
}
