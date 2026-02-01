<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$deleted = DB::table('form_assignments')
    ->where('form_name', 'like', '%Student-Feedback%')
    ->delete();

echo "Deleted {$deleted} Student Feedback form assignment(s).\n";
