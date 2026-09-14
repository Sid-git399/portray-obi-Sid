<?php

declare(strict_types=1);

$files = [
    'app/core/Auth.php',
    'app/core/Csrf.php',
    'app/core/Database.php',
    'app/core/Router.php',
];

foreach ($files as $file) {
    if (!is_file(dirname(__DIR__) . '/' . $file)) {
        throw new RuntimeException("Missing application core file: {$file}");
    }
}

echo "Core application files verified." . PHP_EOL;
