<?php

declare(strict_types=1);

echo "PHP runtime: " . PHP_VERSION . PHP_EOL;
echo "SOAP extension: " . (extension_loaded('soap') ? 'enabled' : 'disabled') . PHP_EOL;
echo "PDO extension: " . (extension_loaded('pdo') ? 'enabled' : 'disabled') . PHP_EOL;
