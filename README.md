# PHPLOC

`phploc` is a tool for quickly measuring the size and analyzing the structure of a PHP project.

## Installation

### PHP Archive (PHAR)

The easiest way to obtain PHPLOC is to download a [PHP Archive (PHAR)](http://php.net/phar) that has all required dependencies of PHPLOC bundled in a single file:

    wget http://pear.phpunit.de/get/phploc.phar
    chmod +x phploc.phar
    mv phploc.phar /usr/local/bin/phploc

You can also immediately use the PHAR after you have downloaded it, of course:

    wget http://pear.phpunit.de/get/phploc.phar
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

## Usage Example

    ➜ ~ phploc /usr/local/src/phpunit/PHPUnit
    phploc 2.0.0 by Sebastian Bergmann.

    Directories                                         17
    Files                                              121

    Size
      Lines of Code (LOC)                            29022
      Comment Lines of Code (CLOC)                   14155 (48.77%)
      Non-Comment Lines of Code (NCLOC)              14867 (51.23%)
      Logical Lines of Code (LLOC)                    3477 (11.98%)
        Classes                                       3307 (95.11%)
          Average Class Length                          29
          Average Method Length                          4
        Functions                                      153 (4.40%)
          Average Function Length                        1
        Not in classes or functions                     17 (0.49%)

    Complexity
      Cyclomatic Complexity / LLOC                    0.51
      Cyclomatic Complexity / Number of Methods       3.36

    Dependencies
      Global Accesses                                   43
        Global Constants                                 1 (2.33%)
        Global Variables                                34 (79.07%)
        Super-Global Variables                           8 (18.60%)
      Attribute Accesses                              1174
        Non-Static                                    1054 (89.78%)
        Static                                         120 (10.22%)
      Method Calls                                    1502
        Non-Static                                     976 (64.98%)
        Static                                         526 (35.02%)

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
      Functions                                        142
        Named Functions                                138 (97.18%)
        Anonymous Functions                              4 (2.82%)
      Constants                                         36
        Global Constants                                 2 (5.56%)
        Class Constants                                 34 (94.44%)
