# PHPLOC

`phploc` is a tool for quickly measuring the size of a PHP project.

## Forked Changes

## Version 8.0.0
* **[2025-04-11 13:02:04 CDT]** Added support for PHPUnit v10.


## Installation

This tool is distributed as a [PHP Archive (PHAR)](https://php.net/phar):

```bash
$ wget https://phar.phpunit.de/phploc.phar

$ php phploc.phar --version
```

Using [Phive](https://phar.io/) is the recommended way for managing the tool dependencies of your project:

```bash
$ phive install phploc

$ ./tools/phploc --version
```

**[It is not recommended to use Composer to download and install this tool.](https://docs.phpunit.de/en/main/installation.html#phar-or-composer)**

## Usage Example

```
$ php phploc.phar src
phploc 8.0-dev by Sebastian Bergmann.

Directories:                                        104
Files:                                              856

Lines of Code (LOC):                             67,955
Comment Lines of Code (CLOC):                    19,533 (28.74%)
Non-Comment Lines of Code (NCLOC):               48,422 (71.26%)
Logical Lines of Code (LLOC):                    18,478 (27.19%)

Classes or Traits                                   662
  Methods                                         3,389
    Cyclomatic Complexity
      Lowest                                       1.00
      Average                                      2.00
      Highest                                    156.00

Functions                                           185
  Cyclomatic Complexity
    Lowest                                         1.00
    Average                                        1.00
    Highest                                        1.00
```
