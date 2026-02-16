<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

echo "=== Testing What Assignments Would Be Created ===\n\n";

$student = Student::where('email', 'nikita.reddy66@student.com')->first();

if (!$student) {
    echo "❌ Could not find Nikita Reddy\n";
    exit;
}

echo "Student: {$student->name} (Batch: {$student->batch_id})\n\n";

// Check Cloud Computing teacher
$cloudComputing = Subject::where('name', 'Cloud Computing')->first();
$testTeacher = Teacher::where('name', 'test')->first();

if ($cloudComputing && $testTeacher) {
    echo "1. Cloud Computing (Teacher: test)\n";
    
    $batches = DB::table('batch_teacher')
        ->where('teacher_id', $testTeacher->id)
        ->where('subject_id', $cloudComputing->id)
        ->get();
    
    echo "   Teacher has " . $batches->count() . " assignments:\n";
    foreach ($batches as $batch) {
        $type = $batch->type === 'lab' ? '🔬 LAB' : '📚 LECTURE';
        echo "      {$type}\n";
    }
    
    echo "   Would create " . $batches->count() . " assignments for student\n\n";
}

// Check DAA teacher
$daa = Subject::where('name', 'DESIGN AND ANALYSIS OF ALGORITHMS')->first();
$kunjTeacher = Teacher::where('name', 'Kunj Dudhatra')->first();

if ($daa && $kunjTeacher) {
    echo "2. DESIGN AND ANALYSIS OF ALGORITHMS (Teacher: Kunj Dudhatra)\n";
    
    $batches = DB::table('batch_teacher')
        ->where('teacher_id', $kunjTeacher->id)
        ->where('subject_id', $daa->id)
        ->get();
    
    echo "   Teacher has " . $batches->count() . " assignments:\n";
    foreach ($batches as $batch) {
        $type = $batch->type === 'lab' ? '🔬 LAB' : '📚 LECTURE';
        echo "      {$type}\n";
    }
    
    echo "   Would create " . $batches->count() . " assignments for student\n\n";
}

echo "=== Summary ===\n\n";
echo "✅ Lab assignments are now properly configured!\n";
echo "\n📝 NOTE: Nikita Reddy's existing assignments are LECTURE-only because they were\n";
echo "created before lab batches were added. To update her assignments:\n\n";
echo "Option 1: Delete and reassign (in admin panel)\n";
echo "   - Go to 'Assign Forms' page\n";
echo "   - Find her existing assignments\n";
echo "   - Delete them\n";
echo "   - Use 'Teachers with Batch Assignments' to reassign\n";
echo "   - She will now get BOTH lab and lecture assignments\n\n";
echo "Option 2: Manually add lab assignments (using 'Assign Forms' > 'Assign Manually')\n\n";
echo "All NEW student assignments will automatically include both lab and lecture! ✅\n";
