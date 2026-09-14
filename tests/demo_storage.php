<?php

declare(strict_types=1);

$storage = dirname(__DIR__) . '/storage/demo_reports';

if (!is_dir($storage)) {
    throw new RuntimeException('Demo report storage is missing.');
}

if (!is_writable($storage)) {
    fwrite(STDERR, "Warning: demo report storage is not writable." . PHP_EOL);
}

echo "Demo report storage verified." . PHP_EOL;
