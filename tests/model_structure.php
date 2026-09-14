<?php

declare(strict_types=1);

$required = [
    'app/models/Utilisateur.php',
    'app/models/Categorie.php',
    'app/models/Rapport.php',
    'app/models/ParametreRapport.php',
    'app/models/Historique.php',
];

foreach ($required as $file) {
    if (!is_file(dirname(__DIR__) . '/' . $file)) {
        throw new RuntimeException("Missing model: {$file}");
    }
}

echo "Model structure verified." . PHP_EOL;
