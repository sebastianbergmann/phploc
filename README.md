[![Latest Stable Version](https://poser.pugx.org/phploc/phploc/v/stable.png)](https://packagist.org/packages/phploc/phploc)
[![Build Status](https://travis-ci.org/sebastianbergmann/phploc.png?branch=master)](https://travis-ci.org/sebastianbergmann/phploc)

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
phploc 3.1.0 by Sebastian Bergmann.

Directories                                          4
Files                                               11

Size
  Lines of Code (LOC)                             2087
  Comment Lines of Code (CLOC)                     314 (15.05%)
  Non-Comment Lines of Code (NCLOC)               1773 (84.95%)
  Logical Lines of Code (LLOC)                     417 (19.98%)
    Classes                                        386 (92.57%)
      Average Class Length                          35
        Minimum Class Length                         0
        Maximum Class Length                       172
      Average Method Length                          2
        Minimum Method Length                        1
        Maximum Method Length                      117
    Functions                                        0 (0.00%)
      Average Function Length                        0
    Not in classes or functions                     31 (7.43%)

Cyclomatic Complexity
  Average Complexity per LLOC                     0.47
  Average Complexity per Class                   19.00
    Minimum Class Complexity                      1.00
    Maximum Class Complexity                    137.00
  Average Complexity per Method                   2.46
    Minimum Method Complexity                     1.00
    Maximum Method Complexity                    96.00

Dependencies
  Global Accesses                                    0
    Global Constants                                 0 (0.00%)
    Global Variables                                 0 (0.00%)
    Super-Global Variables                           0 (0.00%)
  Attribute Accesses                                91
    Non-Static                                      91 (100.00%)
    Static                                           0 (0.00%)
  Method Calls                                     313
    Non-Static                                     307 (98.08%)
    Static                                           6 (1.92%)

Structure
  Namespaces                                         4
  Interfaces                                         1
  Traits                                             0
  Classes                                           10
    Abstract Classes                                 0 (0.00%)
    Concrete Classes                                10 (100.00%)
  Methods                                          136
    Scope
      Non-Static Methods                           136 (100.00%)
      Static Methods                                 0 (0.00%)
    Visibility
      Public Methods                               105 (77.21%)
      Non-Public Methods                            31 (22.79%)
  Functions                                          0
    Named Functions                                  0 (0.00%)
    Anonymous Functions                              0 (0.00%)
  Constants                                          0
    Global Constants                                 0 (0.00%)
    Class Constants                                  0 (0.00%)
```

### Analyse a directory for each revision in a Git repository and write the result in CSV format

```
$ phploc --log-csv /tmp/log.csv --progress --git-repository . src
phploc 3.1.0 by Sebastian Bergmann.

 177/177 [============================] 100%
```

