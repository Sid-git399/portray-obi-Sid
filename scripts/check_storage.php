<?php

declare(strict_types=1);

$directories = [
    __DIR__ . '/../storage',
    __DIR__ . '/../storage/demo_reports',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        fwrite(STDERR, "Missing directory: {$directory}" . PHP_EOL);
        exit(1);
    }
}

echo "Storage directories are ready." . PHP_EOL;
