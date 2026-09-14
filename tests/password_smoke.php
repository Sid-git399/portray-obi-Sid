<?php

declare(strict_types=1);

$hash = password_hash('password123', PASSWORD_DEFAULT);

if (!password_verify('password123', $hash)) {
    throw new RuntimeException('Password verification failed.');
}

if (password_verify('wrong-password', $hash)) {
    throw new RuntimeException('Invalid password was accepted.');
}

echo "Password hashing smoke test passed." . PHP_EOL;
