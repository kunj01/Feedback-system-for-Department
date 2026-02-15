<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Students Table Schema ===\n\n";

$columns = DB::select('PRAGMA table_info(students)');

foreach ($columns as $column) {
    echo "{$column->name} ({$column->type})";
    if ($column->notnull) echo " NOT NULL";
    if ($column->dflt_value !== null) echo " DEFAULT {$column->dflt_value}";
    echo "\n";
}

echo "\n=== Checking sample student data ===\n";
$student = DB::table('students')->first();
if ($student) {
    echo "\nFirst student raw data:\n";
    foreach ((array)$student as $key => $value) {
        if ($key === 'batch' || $key === 'batch_id') {
            echo "  {$key} = " . var_export($value, true) . "\n";
        }
    }
}
