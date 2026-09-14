<?php

declare(strict_types=1);

$token = bin2hex(random_bytes(32));

if (strlen($token) !== 64) {
    throw new RuntimeException('Unexpected CSRF token length.');
}

echo "CSRF token generation smoke test passed." . PHP_EOL;
