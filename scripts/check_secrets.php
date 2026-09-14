<?php

declare(strict_types=1);

$envFile = dirname(__DIR__) . '/.env';

if (file_exists($envFile)) {
    fwrite(STDERR, ".env exists locally and must remain untracked." . PHP_EOL);
}

echo "Environment secret check completed." . PHP_EOL;
