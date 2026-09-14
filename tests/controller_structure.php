<?php

declare(strict_types=1);

$required = [
    'app/controllers/HomeController.php',
    'app/controllers/AuthController.php',
    'app/controllers/CatalogueController.php',
    'app/controllers/RapportController.php',
    'app/controllers/AdminController.php',
];

foreach ($required as $file) {
    if (!is_file(dirname(__DIR__) . '/' . $file)) {
        throw new RuntimeException("Missing controller: {$file}");
    }
}

echo "Controller structure verified." . PHP_EOL;
