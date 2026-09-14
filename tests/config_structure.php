<?php

declare(strict_types=1);

$required = [
    'config/app.php',
    'config/database.php',
    'config/obi.php',
];

foreach ($required as $file) {
    if (!is_file(dirname(__DIR__) . '/' . $file)) {
        throw new RuntimeException("Missing configuration file: {$file}");
    }
}

echo "Configuration files verified." . PHP_EOL;
