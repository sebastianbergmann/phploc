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

For a standalone, system-wide installation via Composer, a `composer.json` similar to the one shown below can be used from an arbitary directory:

    {
        "require": {
            "phploc/phploc": "*"
        },
        "config": {
            "bin-dir": "/usr/local/bin/"
        }
    }

### PEAR Installer

The following two commands (which you may have to run as `root`) are all that is required to install PHPLOC using the PEAR Installer:

    pear config-set auto_discover 1
    pear install pear.phpunit.de/phploc

## Usage Examples

### Analyse a directory and print the result

    ➜ ~ phploc /usr/local/src/phpunit/PHPUnit
    phploc 2.0.1 by Sebastian Bergmann.

    Directories                                         17
    Files                                              121

    Size
      Lines of Code (LOC)                            29047
      Comment Lines of Code (CLOC)                   14022 (48.27%)
      Non-Comment Lines of Code (NCLOC)              15025 (51.73%)
      Logical Lines of Code (LLOC)                    3484 (11.99%)
        Classes                                       3314 (95.12%)
          Average Class Length                          29
          Average Method Length                          4
        Functions                                      153 (4.39%)
          Average Function Length                        1
        Not in classes or functions                     17 (0.49%)

    Complexity
      Cyclomatic Complexity / LLOC                    0.51
      Cyclomatic Complexity / Number of Methods       3.37

    Dependencies
      Global Accesses                                   43
        Global Constants                                 1 (2.33%)
        Global Variables                                34 (79.07%)
        Super-Global Variables                           8 (18.60%)
      Attribute Accesses                              1122
        Non-Static                                    1054 (93.94%)
        Static                                          68 (6.06%)
      Method Calls                                    1503
        Non-Static                                     976 (64.94%)
        Static                                         527 (35.06%)

    Structure
      Namespaces                                         0
      Interfaces                                         6
      Traits                                             0
      Classes                                          113
        Abstract Classes                                 9 (7.96%)
        Concrete Classes                               104 (92.04%)
      Methods                                          750
        Scope
          Non-Static Methods                           507 (67.60%)
          Static Methods                               243 (32.40%)
        Visibility
          Public Method                                526 (70.13%)
          Non-Public Methods                           224 (29.87%)
      Functions                                        143
        Named Functions                                138 (96.50%)
        Anonymous Functions                              5 (3.50%)
      Constants                                         36
        Global Constants                                 2 (5.56%)
        Class Constants                                 34 (94.44%)

### Analyse a directory for each revision in a Git repository and write the result in CSV format

    ➜ ~ phploc --log-csv log.csv --progress --git-repository . src
    phploc 2.0.0-BETA1-11-g188c14e by Sebastian Bergmann.

     295/295 [============================] 100%
