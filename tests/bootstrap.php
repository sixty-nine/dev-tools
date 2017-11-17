<?php

$autoload = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($autoload)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

require_once $autoload;
require_once __DIR__ . '/../src/SixtyNine/DevTools/functions.php';
