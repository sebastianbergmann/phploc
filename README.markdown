phploc
======

**phploc** is a tool for quickly measuring the size of a PHP project.

The goal of **phploc** is not not to replace more sophisticated tools such as [phpcs](http://pear.php.net/PHP_CodeSniffer), [pdepend](http://pdepend.org/), or [phpmd](http://phpmd.org/), but rather to provide an alternative to them when you just need to get a quick understanding of a project's size.

Requirements
------------

* The [tokenizer](http://www.php.net/tokenizer) extension is required to count the Comment Lines of Code (CLOC) and Non-Comment Lines of Code (NCLOC) as well as the number of interfaces, classes, methods, and functions of a project.
* The [bytekit](http://www.bytekit.org/) extension is an optional requirement and is used to count the Executable Lines of Code (ELOC) of a project.

Installation
------------

phploc should be installed using the [PEAR Installer](http://pear.php.net/). This installer is the backbone of PEAR, which provides a distribution system for PHP packages, and is shipped with every release of PHP since version 4.3.0.

The PEAR channel (`pear.phpunit.de`) that is used to distribute phploc needs to be registered with the local PEAR environment:

    sb@ubuntu ~ % pear channel-discover pear.phpunit.de
    Adding Channel "pear.phpunit.de" succeeded
    Discovery of channel "pear.phpunit.de" succeeded

This has to be done only once. Now the PEAR Installer can be used to install packages from the PHPUnit channel:

    sb@ubuntu ~ % pear install phpunit/phploc
    downloading phploc-1.0.0.tgz ...
    Starting to download phploc-1.0.0.tgz (5,834 bytes)
    .....done: 5,834 bytes
    install ok: channel://pear.phpunit.de/phploc-1.0.0

After the installation you can find the phploc source files inside your local PEAR directory; the path is usually `/usr/lib/php/PHPLOC`.

Usage Examples
--------------

    sb@ubuntu ~ % phploc /usr/local/src/ezcomponents/trunk/Workflow
    phploc 1.1.0 by Sebastian Bergmann.

    Directories:                               13
    Files:                                    102

    Lines of Code (LOC):                    14138
    Executable Lines of Code (ELOC):         5748
    Comment Lines of Code (CLOC):            5244
    Non-Comment Lines of Code (NCLOC):       8894

    Interfaces:                                 7
    Classes:                                   90
    Non-Static Methods:                       536
    Static Methods:                            33
    Functions:                                  0

You can use the `--sut` and `--tests` switches to tell `phploc` which directory
contains your tested code (system under test) and which directory contains your
test code.

    sb@ubuntu ~ % phploc --sut   /usr/local/src/ezcomponents/trunk/Workflow/src \
                         --tests /usr/local/src/ezcomponents/trunk/Workflow/tests
    phploc 1.1.0 by Sebastian Bergmann.

    System Under Test
    ---------------------------------------------
    Directories:                               11
    Files:                                     82

    Lines of Code (LOC):                     9576
    Executable Lines of Code (ELOC):         2944
    Comment Lines of Code (CLOC):            4923
    Non-Comment Lines of Code (NCLOC):       4653

    Interfaces:                                 7
    Classes:                                   74
    Non-Static Methods:                       274
    Static Methods:                            22
    Functions:                                  0


    Tests
    ---------------------------------------------
    Lines of Code (LOC):                     4457
    Executable Lines of Code (ELOC):         2753
    Comment Lines of Code (CLOC):             263
    Non-Comment Lines of Code (NCLOC):       4194

    Interfaces:                                 0
    Classes:                                   15
    Non-Static Methods:                       259
    Static Methods:                            11
    Functions:                                  0

