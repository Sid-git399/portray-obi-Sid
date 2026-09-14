<?php

declare(strict_types=1);

function assertTrue(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

assertTrue(function_exists('password_hash'), 'Password hashing API is unavailable.');

echo "Authentication prerequisites passed." . PHP_EOL;
