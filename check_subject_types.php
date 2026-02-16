<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Teacher;
use App\Models\Subject;

echo "=== Checking Subject and Batch Types ===\n\n";

// Check Cloud Computing
$subject1 = Subject::where('name', 'Cloud Computing')->first();
if ($subject1) {
    echo "Subject: Cloud Computing (ID: {$subject1->id})\n";
    echo "Has Lab: " . ($subject1->has_lab ? 'YES' : 'NO') . "\n";
    
    // Find teachers assigned to this subject
    $teachers = Teacher::whereHas('batches', function($q) use ($subject1) {
        $q->where('subject_id', $subject1->id);
    })->with(['batches' => function($q) use ($subject1) {
        $q->where('subject_id', $subject1->id);
    }])->get();
    
    echo "\nTeachers assigned:\n";
    foreach ($teachers as $teacher) {
        echo "  - {$teacher->name}\n";
        foreach ($teacher->batches as $batch) {
            $pivot = $batch->pivot;
            $type = $pivot->type ?? 'NOT SET';
            echo "    Batch: {$batch->name}, Type: {$type}\n";
        }
    }
    echo "\n";
}

// Check DESIGN AND ANALYSIS OF ALGORITHMS
$subject2 = Subject::where('name', 'DESIGN AND ANALYSIS OF ALGORITHMS')->first();
if ($subject2) {
    echo "Subject: DESIGN AND ANALYSIS OF ALGORITHMS (ID: {$subject2->id})\n";
    echo "Has Lab: " . ($subject2->has_lab ? 'YES' : 'NO') . "\n";
    
    // Find teachers assigned to this subject
    $teachers = Teacher::whereHas('batches', function($q) use ($subject2) {
        $q->where('subject_id', $subject2->id);
    })->with(['batches' => function($q) use ($subject2) {
        $q->where('subject_id', $subject2->id);
    }])->get();
    
    echo "\nTeachers assigned:\n";
    foreach ($teachers as $teacher) {
        echo "  - {$teacher->name}\n";
        foreach ($teacher->batches as $batch) {
            $pivot = $batch->pivot;
            $type = $pivot->type ?? 'NOT SET';
            echo "    Batch: {$batch->name}, Type: {$type}\n";
        }
    }
    echo "\n";
}

// Check PROJECT-III (which has lab assignments working)
$subject3 = Subject::where('name', 'PROJECT-III')->first();
if ($subject3) {
    echo "Subject: PROJECT-III (ID: {$subject3->id}) - WORKING LAB EXAMPLE\n";
    echo "Has Lab: " . ($subject3->has_lab ? 'YES' : 'NO') . "\n";
    
    // Find teachers assigned to this subject
    $teachers = Teacher::whereHas('batches', function($q) use ($subject3) {
        $q->where('subject_id', $subject3->id);
    })->with(['batches' => function($q) use ($subject3) {
        $q->where('subject_id', $subject3->id);
    }])->get();
    
    echo "\nTeachers assigned:\n";
    foreach ($teachers as $teacher) {
        echo "  - {$teacher->name}\n";
        foreach ($teacher->batches as $batch) {
            $pivot = $batch->pivot;
            $type = $pivot->type ?? 'NOT SET';
            echo "    Batch: {$batch->name}, Type: {$type}\n";
        }
    }
}

echo "\n✅ Check Complete!\n";
