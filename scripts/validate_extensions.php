<?php

declare(strict_types=1);

$required = [
    'soap',
    'pdo',
];

$failed = [];

foreach ($required as $extension) {
    if (!extension_loaded($extension)) {
        $failed[] = $extension;
    }
}

if ($failed !== []) {
    fwrite(STDERR, 'Missing extensions: ' . implode(', ', $failed) . PHP_EOL);
    exit(1);
}

echo "Required PHP extensions are available." . PHP_EOL;
