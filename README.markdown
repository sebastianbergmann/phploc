phploc
======

**phploc** is a tool for quickly measuring the size of a PHP project.

The goal of **phploc** is not not to replace more sophisticated tools such as [phpcs](http://pear.php.net/PHP_CodeSniffer), [pdepend](http://pdepend.org/), or [phpmd](http://phpmd.org/), but rather to provide an alternative to them when you just need to get a quick understanding of a project's size.

Requirements
------------

* The [tokenizer](http://www.php.net/tokenizer) extension is required to count the Comment Lines of Code (CLOC) and Non-Comment Lines of Code (NCLOC) as well as the number of interfaces, classes, methods, and functions of a project.
* The [parsekit](http://pecl.php.net/package/parsekit) extension is an optional requirement and is used to count the Executable Lines of Code (ELOC) of a project.

Installation
------------

    sb@ubuntu ~ % pear install phpunit/phploc
    downloading phploc-1.0.0.tgz ...
    Starting to download phploc-1.0.0.tgz (5,834 bytes)
    .....done: 5,834 bytes
    install ok: channel://pear.phpunit.de/phploc-1.0.0

Usage Example
-------------

    sb@ubuntu ~ % phploc /usr/local/src/phpunit/trunk 
    phploc 1.0.0 by Sebastian Bergmann.

    Directories:                               51
    Files:                                    335

    Lines of Code (LOC):                    60733
    Executable Lines of Code (ELOC):        22378
    Comment Lines of Code (CLOC):           25820
    Non-Comment Lines of Code (NCLOC):      34913

    Interfaces:                                31
    Classes:                                  303
    Functions/Methods:                       2062

