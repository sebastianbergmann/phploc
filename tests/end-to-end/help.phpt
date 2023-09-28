--TEST--
phploc --help
--FILE--
<?php declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

$_SERVER['argv'][] = '--help';

(new SebastianBergmann\PHPLOC\Application)->run($_SERVER['argv']);
--EXPECTF--
phploc %s by Sebastian Bergmann.

Usage:
  phploc [options] <directory>

Options for selecting files:

  --suffix <suffix> Include files with names ending in <suffix> in the analysis
                    (default: .php; can be given multiple times)
  --exclude <path>  Exclude files with <path> in their path from the analysis
                    (can be given multiple times)

  --debug           Print debugging information
