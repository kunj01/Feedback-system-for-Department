<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$subject = App\Models\Subject::where('code', 'IT366')->first();
if (!$subject) {
    echo "Subject IT366 not found\n";
    exit;
}

$responses = App\Models\FormResponse::with(['student.user', 'formAssignment.teacher'])
    ->whereHas('formAssignment', function($q) use ($subject) {
        $q->where('subject_id', $subject->id);
    })
    ->get();

echo "Total Responses: " . $responses->count() . "\n";

if ($responses->count() > 0) {
    $firstResponse = $responses->first();
    echo "First response fields: " . json_encode(array_keys($firstResponse->responses)) . "\n";
    echo "Sample rating structure: " . json_encode($firstResponse->responses['prepare_for_class']) . "\n";
    
    // Test the conversion
    $controller = new App\Http\Controllers\Admin\SubjectAnalysisController();
    $reflector = new ReflectionClass($controller);
    $method = $reflector->getMethod('convertRatingToNumeric');
    $method->setAccessible(true);
    
    $ratingText = $firstResponse->responses['prepare_for_class']['rating'];
    $numericValue = $method->invoke($controller, $ratingText);
    echo "Rating '{$ratingText}' converts to: {$numericValue}\n";
}
