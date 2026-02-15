<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Division;
use App\Models\Batch;

$divisions = ['4-IT-1', '4-IT-2', '6-IT-1', '6-IT-2'];

foreach ($divisions as $divName) {
    $div = Division::where('name', $divName)->first();
    echo "\n{$divName} batches:\n";
    $batches = Batch::where('division_id', $div->id)->orderBy('batch_name')->get();
    foreach ($batches as $batch) {
        echo "  {$batch->batch_name}\n";
    }
    echo "Total: {$batches->count()} batches\n";
}
