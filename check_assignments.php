<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$assignments = DB::table('form_assignments')
    ->where('form_name', 'like', '%Student-Feedback%')
    ->get();

echo json_encode($assignments, JSON_PRETTY_PRINT);
