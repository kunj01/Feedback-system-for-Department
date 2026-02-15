<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
echo "DB_CONNECTION: " . env('DB_CONNECTION') . "\n";
echo "DB_DATABASE: " . env('DB_DATABASE') . "\n";
