<?php

if (!($loader = include __DIR__ . '/../vendor/autoload.php')) {
    throw new RuntimeException('Install dependencies to run test suite. "php composer.phar install --dev"');
}

$loader->add('Intercom\Tests', __DIR__);
