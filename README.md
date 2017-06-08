[![Latest Stable Version](https://img.shields.io/packagist/v/phploc/phploc.svg?style=flat-square)](https://packagist.org/packages/phploc/phploc)
[![Build Status](https://img.shields.io/travis/sebastianbergmann/phploc/master.svg?style=flat-square)](https://travis-ci.org/sebastianbergmann/phploc)

# PHPLOC

`phploc` is a tool for quickly measuring the size and analyzing the structure of a PHP project.

## Installation

### PHP Archive (PHAR)

The easiest way to obtain PHPLOC is to download a [PHP Archive (PHAR)](http://php.net/phar) that has all required dependencies of PHPLOC bundled in a single file:

    $ wget https://phar.phpunit.de/phploc.phar
    $ chmod +x phploc.phar
    $ mv phploc.phar /usr/local/bin/phploc

You can also immediately use the PHAR after you have downloaded it, of course:

    $ wget https://phar.phpunit.de/phploc.phar
    $ php phploc.phar

### Composer

You can add this tool as a local, per-project, development-time dependency to your project using [Composer](https://getcomposer.org/):

    $ composer require --dev phploc/phploc

You can then invoke it using the `vendor/bin/phploc` executable.

## Usage Examples

### Analyse a directory and print the result

```
$ phploc src
phploc 4.0.0 by Sebastian Bergmann.

Directories                                          3
Files                                               10

Size
  Lines of Code (LOC)                             1882
  Comment Lines of Code (CLOC)                     255 (13.55%)
  Non-Comment Lines of Code (NCLOC)               1627 (86.45%)
  Logical Lines of Code (LLOC)                     377 (20.03%)
    Classes                                        351 (93.10%)
      Average Class Length                          35
        Minimum Class Length                         0
        Maximum Class Length                       172
      Average Method Length                          2
        Minimum Method Length                        1
        Maximum Method Length                      117
    Functions                                        0 (0.00%)
      Average Function Length                        0
    Not in classes or functions                     26 (6.90%)

Cyclomatic Complexity
  Average Complexity per LLOC                     0.49
  Average Complexity per Class                   19.60
    Minimum Class Complexity                      1.00
    Maximum Class Complexity                    139.00
  Average Complexity per Method                   2.43
    Minimum Method Complexity                     1.00
    Maximum Method Complexity                    96.00

Dependencies
  Global Accesses                                    0
    Global Constants                                 0 (0.00%)
    Global Variables                                 0 (0.00%)
    Super-Global Variables                           0 (0.00%)
  Attribute Accesses                                85
    Non-Static                                      85 (100.00%)
    Static                                           0 (0.00%)
  Method Calls                                     280
    Non-Static                                     276 (98.57%)
    Static                                           4 (1.43%)

Structure
  Namespaces                                         3
  Interfaces                                         1
  Traits                                             0
  Classes                                            9
    Abstract Classes                                 0 (0.00%)
    Concrete Classes                                 9 (100.00%)
  Methods                                          130
    Scope
      Non-Static Methods                           130 (100.00%)
      Static Methods                                 0 (0.00%)
    Visibility
      Public Methods                               103 (79.23%)
      Non-Public Methods                            27 (20.77%)
  Functions                                          0
    Named Functions                                  0 (0.00%)
    Anonymous Functions                              0 (0.00%)
  Constants                                          0
    Global Constants                                 0 (0.00%)
    Class Constants                                  0 (0.00%)
```
