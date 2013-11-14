[![Latest Stable Version](https://poser.pugx.org/phploc/phploc/v/stable.png)](https://packagist.org/packages/phploc/phploc)
[![Build Status](https://travis-ci.org/sebastianbergmann/phploc.png?branch=master)](https://travis-ci.org/sebastianbergmann/phploc)

# PHPLOC

`phploc` is a tool for quickly measuring the size and analyzing the structure of a PHP project.

## Installation

### PHP Archive (PHAR)

The easiest way to obtain PHPLOC is to download a [PHP Archive (PHAR)](http://php.net/phar) that has all required dependencies of PHPLOC bundled in a single file:

    wget https://phar.phpunit.de/phploc.phar
    chmod +x phploc.phar
    mv phploc.phar /usr/local/bin/phploc

You can also immediately use the PHAR after you have downloaded it, of course:

    wget https://phar.phpunit.de/phploc.phar
    php phploc.phar

### Composer

Simply add a dependency on `phploc/phploc` to your project's `composer.json` file if you use [Composer](http://getcomposer.org/) to manage the dependencies of your project. Here is a minimal example of a `composer.json` file that just defines a development-time dependency on PHPLOC:

    {
        "require-dev": {
            "phploc/phploc": "*"
        }
    }

For a system-wide installation via Composer, you can run:

    composer global require 'phploc/phploc=*'

Make sure you have `~/.composer/vendor/bin/` in your path.

### PEAR Installer

The following two commands (which you may have to run as `root`) are all that is required to install PHPLOC using the PEAR Installer:

    pear config-set auto_discover 1
    pear install pear.phpunit.de/phploc

## Usage Examples

### Analyse a directory and print the result

    ➜ ~ phploc src
    phploc 2.0.4 by Sebastian Bergmann.

    Directories                                          3
    Files                                                8

    Size
      Lines of Code (LOC)                             1858
      Comment Lines of Code (CLOC)                     560 (30.14%)
      Non-Comment Lines of Code (NCLOC)               1298 (69.86%)
      Logical Lines of Code (LLOC)                     289 (15.55%)
        Classes                                        260 (89.97%)
          Average Class Length                          37
          Average Method Length                          9
        Functions                                        5 (1.73%)
          Average Function Length                        5
        Not in classes or functions                     24 (8.30%)

    Complexity
      Cyclomatic Complexity / LLOC                    0.67
      Cyclomatic Complexity / Number of Methods       7.86

    Dependencies
      Global Accesses                                    2
        Global Constants                                 2 (100.00%)
        Global Variables                                 0 (0.00%)
        Super-Global Variables                           0 (0.00%)
      Attribute Accesses                                48
        Non-Static                                      48 (100.00%)
        Static                                           0 (0.00%)
      Method Calls                                      96
        Non-Static                                      91 (94.79%)
        Static                                           5 (5.21%)

    Structure
      Namespaces                                         4
      Interfaces                                         0
      Traits                                             0
      Classes                                            7
        Abstract Classes                                 0 (0.00%)
        Concrete Classes                                 7 (100.00%)
      Methods                                           28
        Scope
          Non-Static Methods                            28 (100.00%)
          Static Methods                                 0 (0.00%)
        Visibility
          Public Method                                 10 (35.71%)
          Non-Public Methods                            18 (64.29%)
      Functions                                          1
        Named Functions                                  0 (0.00%)
        Anonymous Functions                              1 (100.00%)
      Constants                                          1
        Global Constants                                 1 (100.00%)
        Class Constants                                  0 (0.00%)

### Analyse a directory for each revision in a Git repository and write the result in CSV format

    ➜ ~ phploc --log-csv log.csv --progress --git-repository . src
    phploc 2.0.4 by Sebastian Bergmann.

     295/295 [============================] 100%
