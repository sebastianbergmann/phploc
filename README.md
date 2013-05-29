# PHPLOC

`phploc` is a tool for quickly measuring the size and analyzing the structure of a PHP project.

## Installation

There are three supported ways of installing PHPLOC.

You can use the [PEAR Installer](http://pear.php.net/manual/en/guide.users.commandline.cli.php) or [Composer](http://getcomposer.org/) to download and install PHPLOC as well as its dependencies. You can also download a [PHP Archive (PHAR)](http://php.net/phar) of PHPLOC that has all required (as well as some optional) dependencies of PHPLOC bundled in a single file.

### PEAR Installer

The following two commands (which you may have to run as `root`) are all that is required to install PHPLOC using the PEAR Installer:

    pear config-set auto_discover 1
    pear install pear.phpunit.de/phploc

### Composer

To add PHPLOC as a local, per-project dependency to your project, simply add a dependency on `sebastian/phploc` to your project's `composer.json` file. Here is a minimal example of a `composer.json` file that just defines a development-time dependency on PHPLOC 2.0:

    {
        "require-dev": {
            "sebastian/phploc": "2.0.*"
        }
    }

### PHP Archive (PHAR)

    wget http://pear.phpunit.de/get/phploc.phar
    chmod +x phploc.phar

## Usage Example

    ➜ ~ phploc /usr/local/src/phpunit/PHPUnit
    phploc 2.0.0 by Sebastian Bergmann.

    Directories:                                         17
    Files:                                              121

    Lines of Code (LOC):                              29022
      Cyclomatic Complexity / Lines of Code:           0.12
    Comment Lines of Code (CLOC):                     14155
    Non-Comment Lines of Code (NCLOC):                14867

    Namespaces:                                           0
    Interfaces:                                           6
    Traits:                                               0
    Classes:                                            113
      Abstract:                                           9 (7.96%)
      Concrete:                                         104 (92.04%)
      Average Class Length (NCLOC):                     126
    Methods:                                            750
      Scope:
        Non-Static:                                     507 (67.60%)
        Static:                                         243 (32.40%)
      Visibility:
        Public:                                         526 (70.13%)
        Non-Public:                                     224 (29.87%)
      Average Method Length (NCLOC):                     19
      Cyclomatic Complexity / Number of Methods:       3.36

    Anonymous Functions:                                  4
    Functions:                                          138

    Constants:                                           36
      Global constants:                                   2
      Class constants:                                   34

    Dependencies:
      Attribute Access:                                 729
        Non-Static:                                     647 (88.75%)
        Static:                                          82 (11.25%)
      Method Call:                                     1947
        Non-Static:                                    1383 (71.03%)
        Static:                                         564 (28.97%)
