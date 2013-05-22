# PHPLOC

`phploc` is a tool for quickly measuring the size and analyzing the structure of a PHP project.

## Installation

There a two supported ways of installing PHPLOC.

You can use the [PEAR Installer](http://pear.php.net/manual/en/guide.users.commandline.cli.php) to download and install PHPLOC as well as its dependencies. You can also download a [PHP Archive (PHAR)](http://php.net/phar) of PHPLOC that has all required dependencies of PHPLOC bundled in a single file.

### PEAR Installer

The following two commands (which you may have to run as `root`) are all that is required to install PHPLOC using the PEAR Installer:

    pear config-set auto_discover 1
    pear install pear.phpunit.de/phploc

### PHP Archive (PHAR)

    wget http://pear.phpunit.de/get/phploc.phar
    chmod +x phploc.phar

## Usage Example

    ➜ ~ phploc /usr/local/src/phpunit/PHPUnit
    phploc 1.7.0 by Sebastian Bergmann.

    Directories:                                         16
    Files:                                              117

    Lines of Code (LOC):                              27640
      Cyclomatic Complexity / Lines of Code:           0.12
    Comment Lines of Code (CLOC):                     13771
    Non-Comment Lines of Code (NCLOC):                13869

    Namespaces:                                           0
    Interfaces:                                           6
    Traits:                                               0
    Classes:                                            109
      Abstract:                                           9 (8.26%)
      Concrete:                                         100 (91.74%)
      Average Class Length (NCLOC):                     125
    Methods:                                            723
      Scope:
        Non-Static:                                     498 (68.88%)
        Static:                                         225 (31.12%)
      Visibility:
        Public:                                         510 (70.54%)
        Non-Public:                                     213 (29.46%)
      Average Method Length (NCLOC):                     18
      Cyclomatic Complexity / Number of Methods:       3.34

    Anonymous Functions:                                  0
    Functions:                                          128

    Constants:                                           33
      Global constants:                                   1
      Class constants:                                   32
