--TEST--
phploc --suffix .lib ../_fixture/example_function.php ../_fixture
--FILE--
<?php declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

$_SERVER['argv'][] = '--suffix';
$_SERVER['argv'][] = '.lib';
$_SERVER['argv'][] = __DIR__ . '/../_fixture';

(new SebastianBergmann\PHPLOC\Application)->run($_SERVER['argv']);
--EXPECTF--
phploc %s by Sebastian Bergmann.

Directories:                                          1
Files:                                                5

Lines of Code (LOC):                                195
Comment Lines of Code (CLOC):                        40 (20.51%)
Non-Comment Lines of Code (NCLOC):                  155 (79.49%)
Logical Lines of Code (LLOC):                        53 (27.18%)

Classes or Traits                                     2
  Methods                                             2
    Cyclomatic Complexity
      Lowest                                      14.00
      Average                                     14.00
      Highest                                     14.00

Functions                                             2
  Cyclomatic Complexity
    Lowest                                        14.00
    Average                                       14.00
    Highest                                       14.00
