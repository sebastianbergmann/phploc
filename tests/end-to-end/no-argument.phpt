--TEST--
phploc
--FILE--
<?php declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

(new SebastianBergmann\PHPLOC\Application)->run($_SERVER['argv']);
--EXPECTF--
phploc %s by Sebastian Bergmann.

No directory specified
