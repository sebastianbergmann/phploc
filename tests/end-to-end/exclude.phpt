--TEST--
phploc --exclude ../_fixture/example_function.php ../_fixture
--FILE--
<?php declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

$_SERVER['argv'][] = '--exclude';
$_SERVER['argv'][] = __DIR__ . '/../_fixture/example_function.php';
$_SERVER['argv'][] = __DIR__ . '/../_fixture';

(new SebastianBergmann\PHPLOC\Application)->run($_SERVER['argv']);
--EXPECTF--
phploc %s by Sebastian Bergmann.

Directories:                                          1
Files:                                                3

Lines of Code (LOC):                                109
Comment Lines of Code (CLOC):                        24 (22.02%)
Non-Comment Lines of Code (NCLOC):                   85 (77.98%)
Logical Lines of Code (LLOC):                        27 (24.77%)

Classes or Traits                                     2
  Methods                                             2
    Cyclomatic Complexity
      Lowest                                      14.00
      Average                                     14.00
      Highest                                     14.00
