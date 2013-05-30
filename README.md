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

To add PHPLOC as a local, per-project dependency to your project, simply add a dependency on `phploc/phploc` to your project's `composer.json` file. Here is a minimal example of a `composer.json` file that just defines a development-time dependency on PHPLOC 2.0:

    {
        "require-dev": {
            "phploc/phploc": "2.0.*"
        }
    }

### PHP Archive (PHAR)

    wget http://pear.phpunit.de/get/phploc.phar
    chmod +x phploc.phar

## Usage Example

    ➜ ~ phploc /usr/local/src/phpunit/PHPUnit
    phploc 2.0.0 by Sebastian Bergmann.

    Directories                                         17
    Files                                              121

    Size
      Lines of Code (LOC)                            29022
      Logical Lines of Code (LLOC)                    3477
        Classes                                       3307 (95.11%)
          Average Class Length                          29
          Average Method Length                          4
        Functions                                      153 (4.40%)
          Average Function Length                        1
        Not in classes or functions                     17 (0.49%)
      Comment Lines of Code (CLOC)                   14155

    Complexity
      Cyclomatic Complexity / LLOC                    0.51
      Cyclomatic Complexity / Number of Methods       3.36

    Dependencies
      Global Accesses                                   43
        Global Constants                                 1 (2.33%)
        Global Variables                                34 (79.07%)
        Super-Global Variables                           8 (18.60%)
      Attribute Accesses                               729
        Non-Static                                     647 (88.75%)
        Static                                          82 (11.25%)
      Method Calls                                    1947
        Non-Static                                    1383 (71.03%)
        Static                                         564 (28.97%)

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
