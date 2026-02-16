<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Batch;
use Illuminate\Support\Facades\DB;

echo "=== Adding Lab Batch Assignments ===\n\n";

// Add lab batch for Cloud Computing (test teacher)
$cloudComputing = Subject::where('name', 'Cloud Computing')->first();
$testTeacher = Teacher::where('name', 'test')->first();

if ($cloudComputing && $testTeacher) {
    echo "1. Adding lab batch for Cloud Computing (teacher: test)...\n";
    
    // Get the batch that the teacher is already assigned to
    $existingBatch = $testTeacher->batches()->wherePivot('subject_id', $cloudComputing->id)->first();
    
    if ($existingBatch) {
        // Check if lab assignment already exists
        $labExists = DB::table('batch_teacher')
            ->where('teacher_id', $testTeacher->id)
            ->where('batch_id', $existingBatch->id)
            ->where('subject_id', $cloudComputing->id)
            ->where('type', 'lab')
            ->exists();
        
        if (!$labExists) {
            // Add lab type assignment
            DB::table('batch_teacher')->insert([
                'teacher_id' => $testTeacher->id,
                'batch_id' => $existingBatch->id,
                'subject_id' => $cloudComputing->id,
                'type' => 'lab',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "   ✅ Lab batch added for Cloud Computing\n";
        } else {
            echo "   ℹ️  Lab batch already exists for Cloud Computing\n";
        }
    } else {
        echo "   ⚠️  No existing batch found for teacher 'test'\n";
    }
} else {
    echo "   ❌ Could not find Cloud Computing subject or test teacher\n";
}

echo "\n";

// Add lab batch for DESIGN AND ANALYSIS OF ALGORITHMS (Kunj Dudhatra)
$daa = Subject::where('name', 'DESIGN AND ANALYSIS OF ALGORITHMS')->first();
$kunjTeacher = Teacher::where('name', 'Kunj Dudhatra')->first();

if ($daa && $kunjTeacher) {
    echo "2. Setting up DAA for lab and adding lab batch (teacher: Kunj Dudhatra)...\n";
    
    // First, set has_lab to true for DAA
    if (!$daa->has_lab) {
        $daa->has_lab = true;
        $daa->save();
        echo "   ✅ Set has_lab = true for DESIGN AND ANALYSIS OF ALGORITHMS\n";
    }
    
    // Get the batch that the teacher is already assigned to
    $existingBatch = $kunjTeacher->batches()->wherePivot('subject_id', $daa->id)->first();
    
    if ($existingBatch) {
        // Check if lab assignment already exists
        $labExists = DB::table('batch_teacher')
            ->where('teacher_id', $kunjTeacher->id)
            ->where('batch_id', $existingBatch->id)
            ->where('subject_id', $daa->id)
            ->where('type', 'lab')
            ->exists();
        
        if (!$labExists) {
            // Add lab type assignment
            DB::table('batch_teacher')->insert([
                'teacher_id' => $kunjTeacher->id,
                'batch_id' => $existingBatch->id,
                'subject_id' => $daa->id,
                'type' => 'lab',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "   ✅ Lab batch added for DESIGN AND ANALYSIS OF ALGORITHMS\n";
        } else {
            echo "   ℹ️  Lab batch already exists for DESIGN AND ANALYSIS OF ALGORITHMS\n";
        }
    } else {
        echo "   ⚠️  No existing batch found for teacher 'Kunj Dudhatra' and DAA\n";
    }
} else {
    echo "   ❌ Could not find DESIGN AND ANALYSIS OF ALGORITHMS subject or Kunj Dudhatra teacher\n";
}

echo "\n=== Verification ===\n\n";

// Verify the changes
if ($cloudComputing && $testTeacher) {
    $batches = DB::table('batch_teacher')
        ->where('teacher_id', $testTeacher->id)
        ->where('subject_id', $cloudComputing->id)
        ->get();
    
    echo "Cloud Computing assignments for teacher 'test':\n";
    foreach ($batches as $batch) {
        echo "  - Type: {$batch->type}\n";
    }
    echo "\n";
}

if ($daa && $kunjTeacher) {
    $batches = DB::table('batch_teacher')
        ->where('teacher_id', $kunjTeacher->id)
        ->where('subject_id', $daa->id)
        ->get();
    
    echo "DESIGN AND ANALYSIS OF ALGORITHMS assignments for teacher 'Kunj Dudhatra':\n";
    foreach ($batches as $batch) {
        echo "  - Type: {$batch->type}\n";
    }
}

echo "\n✅ Lab batches have been configured!\n";
echo "\nNow when you assign students using 'Teachers with Batch Assignments',\n";
echo "they will receive BOTH lecture and lab assignments automatically.\n";
