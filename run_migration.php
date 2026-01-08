<?php

// Direct migration runner - bypasses terminal issues
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "Starting migration...\n";

$status = $kernel->call('migrate', [
    '--force' => true,
]);

if ($status === 0) {
    echo "\n✓ Migration completed successfully!\n";
    echo "The form_responses table has been created.\n";
    echo "You can now submit the form.\n";
} else {
    echo "\n✗ Migration failed with status code: $status\n";
}

echo "\nPress any key to continue...";
if (PHP_OS_FAMILY === 'Windows') {
    system('pause > nul');
} else {
    readline();
}
