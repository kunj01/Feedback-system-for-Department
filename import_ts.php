<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

echo "=== Importing Subjects and Teachers ===" . PHP_EOL . PHP_EOL;

// Check if filename provided
if ($argc < 2) {
    echo "Usage: php import_ts.php <filename.json>" . PHP_EOL;
    echo "Example: php import_ts.php demo_subjects_teachers_2026-02-04_001530.json" . PHP_EOL;
    exit(1);
}

$filename = $argv[1];

if (!file_exists($filename)) {
    echo "✗ Error: File not found: {$filename}" . PHP_EOL;
    exit(1);
}

DB::beginTransaction();

try {
    // Read JSON file
    $json = file_get_contents($filename);
    $data = json_decode($json, true);
    
    if (!$data) {
        throw new \Exception("Invalid JSON file");
    }
    
    echo "File: {$filename}" . PHP_EOL;
    echo "Exported at: {$data['exported_at']}" . PHP_EOL;
    echo PHP_EOL;
    
    // Clear existing data
    echo "Clearing existing data..." . PHP_EOL;
    DB::table('subject_teacher')->truncate();
    Teacher::truncate();
    Subject::truncate();
    echo "✓ Cleared" . PHP_EOL . PHP_EOL;
    
    // Import Subjects
    echo "Importing Subjects..." . PHP_EOL;
    $subjectIdMap = [];
    foreach ($data['subjects'] as $subjectData) {
        $oldId = $subjectData['id'];
        unset($subjectData['id']); // Let database auto-increment
        
        $subject = Subject::create($subjectData);
        $subjectIdMap[$oldId] = $subject->id;
        
        echo "  ✓ {$subject->code} - {$subject->name}" . PHP_EOL;
    }
    echo PHP_EOL;
    
    // Import Teachers
    echo "Importing Teachers..." . PHP_EOL;
    $teacherIdMap = [];
    foreach ($data['teachers'] as $teacherData) {
        $oldId = $teacherData['id'];
        unset($teacherData['id']); // Let database auto-increment
        
        $teacher = Teacher::create($teacherData);
        $teacherIdMap[$oldId] = $teacher->id;
        
        echo "  ✓ {$teacher->name} ({$teacher->email})" . PHP_EOL;
    }
    echo PHP_EOL;
    
    // Import Subject-Teacher Relations
    echo "Importing Subject-Teacher Relations..." . PHP_EOL;
    foreach ($data['subject_teacher_relations'] as $relation) {
        $newSubjectId = $subjectIdMap[$relation['subject_id']] ?? null;
        $newTeacherId = $teacherIdMap[$relation['teacher_id']] ?? null;
        
        if ($newSubjectId && $newTeacherId) {
            DB::table('subject_teacher')->insert([
                'subject_id' => $newSubjectId,
                'teacher_id' => $newTeacherId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $subject = Subject::find($newSubjectId);
            $teacher = Teacher::find($newTeacherId);
            echo "  ✓ {$subject->code} → {$teacher->name}" . PHP_EOL;
        }
    }
    
    DB::commit();
    
    echo PHP_EOL;
    echo "╔════════════════════════════════════════════════════════════╗" . PHP_EOL;
    echo "║                    IMPORT COMPLETED!                       ║" . PHP_EOL;
    echo "╚════════════════════════════════════════════════════════════╝" . PHP_EOL;
    echo PHP_EOL;
    echo "Summary:" . PHP_EOL;
    echo "  • Subjects imported: " . count($data['subjects']) . PHP_EOL;
    echo "  • Teachers imported: " . count($data['teachers']) . PHP_EOL;
    echo "  • Relationships imported: " . count($data['subject_teacher_relations']) . PHP_EOL;
    echo PHP_EOL;
    echo "✓ Data is ready for demo!" . PHP_EOL;
    echo PHP_EOL;

} catch (\Exception $e) {
    DB::rollBack();
    echo PHP_EOL;
    echo "✗ Error: " . $e->getMessage() . PHP_EOL;
    echo "Import rolled back." . PHP_EOL;
    exit(1);
}
