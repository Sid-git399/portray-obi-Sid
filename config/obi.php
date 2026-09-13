<?php

return [
    'mode'     => $_ENV['OBI_MODE'] ?? 'demo', // 'live' ou 'demo'
    'wsdl'     => $_ENV['OBI_WSDL'] ?? '',
    'username' => $_ENV['OBI_USERNAME'] ?? '',
    'password' => $_ENV['OBI_PASSWORD'] ?? '',
    'timeout'  => (int) ($_ENV['OBI_TIMEOUT'] ?? 30),
    'demo_dir' => __DIR__ . '/../storage/demo_reports',
];
