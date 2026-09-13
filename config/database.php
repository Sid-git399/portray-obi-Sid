<?php

return [
    'tns'      => $_ENV['DB_TNS'] ?? 'localhost/XE',
    'username' => $_ENV['DB_USER'] ?? '',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
];
