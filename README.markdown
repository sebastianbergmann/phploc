phploc
======

**phploc** is a tool for quickly measuring the size and analyzing the structure of a PHP project.

Installation
------------

`phploc` should be installed using the PEAR Installer, the backbone of the [PHP Extension and Application Repository](http://pear.php.net/) that provides a distribution system for PHP packages.

Depending on your OS distribution and/or your PHP environment, you may need to install PEAR or update your existing PEAR installation before you can proceed with the following instructions. `sudo pear upgrade PEAR` usually suffices to upgrade an existing PEAR installation. The [PEAR Manual ](http://pear.php.net/manual/en/installation.getting.php) explains how to perform a fresh installation of PEAR.

The following two commands (which you may have to run as `root`) are all that is required to install `phploc` using the PEAR Installer:

    pear config-set auto_discover 1
    pear install pear.phpunit.de/phploc

After the installation you can find the `phploc` source files inside your local PEAR directory; the path is usually `/usr/lib/php/PHPLOC`.

Usage Examples
--------------

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
