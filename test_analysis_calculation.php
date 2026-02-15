<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$subject = App\Models\Subject::where('code', 'IT366')->first();
if (!$subject) {
    echo "Subject IT366 not found\n";
    exit;
}

$controller = new App\Http\Controllers\Admin\SubjectAnalysisController();

// Use reflection to call the private show method  
$reflector = new ReflectionClass($controller);
$showMethod = $reflector->getMethod('show');

try {
    // Capture the view data
    $result = $showMethod->invoke($controller, $subject->id);
    echo "Analysis generated successfully!\n";
    
    // Extract the analysis data
    if ($result instanceof Illuminate\View\View) {
        $analysis = $result->getData()['analysis'];
        echo "\nOverall Average: " . $analysis['overall_average'] . "/5.0\n";
        echo "Total Responses: " . $analysis['total_responses'] . "\n";
        echo "\nRating Distribution:\n";
        foreach ($analysis['rating_distribution'] as $rating => $percentage) {
            echo "  {$rating}: {$percentage}%\n";
        }
        
        if (!empty($analysis['teacher_breakdown'])) {
            echo "\nTeacher Breakdown:\n";
            foreach ($analysis['teacher_breakdown'] as $teacher) {
                echo "  {$teacher['name']}: {$teacher['average_rating']}/5.0 ({$teacher['response_count']} responses)\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
