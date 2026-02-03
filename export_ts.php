<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

echo "=== Exporting Subjects and Teachers ===" . PHP_EOL . PHP_EOL;

try {
    $data = [
        'exported_at' => now()->toDateTimeString(),
        'subjects' => [],
        'teachers' => [],
        'subject_teacher_relations' => []
    ];
    
    // Export Subjects
    $subjects = Subject::all();
    foreach ($subjects as $subject) {
        $data['subjects'][] = [
            'id' => $subject->id,
            'name' => $subject->name,
            'code' => $subject->code,
            'semester' => $subject->semester,
            'credits' => $subject->credits,
            'sort_order' => $subject->sort_order,
            'is_active' => $subject->is_active,
        ];
    }
    
    // Export Teachers
    $teachers = Teacher::all();
    foreach ($teachers as $teacher) {
        $data['teachers'][] = [
            'id' => $teacher->id,
            'name' => $teacher->name,
            'email' => $teacher->email,
            'phone' => $teacher->phone,
            'department' => $teacher->department,
            'designation' => $teacher->designation,
            'is_active' => $teacher->is_active,
        ];
    }
    
    // Export Subject-Teacher Relations
    $relations = DB::table('subject_teacher')->get();
    foreach ($relations as $relation) {
        $data['subject_teacher_relations'][] = [
            'subject_id' => $relation->subject_id,
            'teacher_id' => $relation->teacher_id,
        ];
    }
    
    // Save to JSON file
    $filename = 'demo_subjects_teachers_' . date('Y-m-d_His') . '.json';
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
    
    echo "✓ Export Successful!" . PHP_EOL;
    echo PHP_EOL;
    echo "Summary:" . PHP_EOL;
    echo "  • Subjects exported: " . count($data['subjects']) . PHP_EOL;
    echo "  • Teachers exported: " . count($data['teachers']) . PHP_EOL;
    echo "  • Relationships exported: " . count($data['subject_teacher_relations']) . PHP_EOL;
    echo PHP_EOL;
    echo "File saved: {$filename}" . PHP_EOL;
    echo PHP_EOL;
    echo "Copy this file to the other PC and run:" . PHP_EOL;
    echo "  php import_ts.php {$filename}" . PHP_EOL;
    echo PHP_EOL;

} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
