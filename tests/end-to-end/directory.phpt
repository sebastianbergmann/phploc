--TEST--
phploc ../_fixture
--FILE--
<?php declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

$_SERVER['argv'][] = __DIR__ . '/../_fixture';

(new SebastianBergmann\PHPLOC\Application)->run($_SERVER['argv']);
--EXPECTF--
phploc %s by Sebastian Bergmann.

Directories:                                          1
Files:                                                4

Lines of Code (LOC):                                152
Comment Lines of Code (CLOC):                        32 (21.05%)
Non-Comment Lines of Code (NCLOC):                  120 (78.95%)
Logical Lines of Code (LLOC):                        40 (26.32%)

Classes or Traits                                     2
  Methods                                             2
    Cyclomatic Complexity
      Lowest                                      14.00
      Average                                     14.00
      Highest                                     14.00

Functions                                             1
  Cyclomatic Complexity
    Lowest                                        14.00
    Average                                       14.00
    Highest                                       14.00
