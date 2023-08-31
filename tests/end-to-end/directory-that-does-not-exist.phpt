--TEST--
phploc ../_fixture
--FILE--
<?php declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

$_SERVER['argv'][] = 'does-not-exist';

(new SebastianBergmann\PHPLOC\Application)->run($_SERVER['argv']);
--EXPECTF--
phploc %s by Sebastian Bergmann.

No files found to scan
