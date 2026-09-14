<?php

declare(strict_types=1);

$service = dirname(__DIR__) . '/app/services/ObiPublisherClient.php';

if (!is_file($service)) {
    throw new RuntimeException('OBI Publisher service is missing.');
}

echo "OBI service structure verified." . PHP_EOL;
