# Changes in PHPLOC

All notable changes in PHPLOC are documented in this file using the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## [6.0.2] - 2020-02-28

### Fixed

* Fixed [#207](https://github.com/sebastianbergmann/phploc/issues/207): `TypeError` in `DOMDocument::createElement()` calls

## [6.0.1] - 2020-02-27

### Fixed

* Fixed [#205](https://github.com/sebastianbergmann/phploc/pull/205): `TypeError` in `ini_set()` calls

## [6.0.0] - 2020-02-20

### Removed

* This tool is no longer supported on PHP 7.2

## [5.0.0] - 2019-03-16

### Fixed

* Fixed [#182](https://github.com/sebastianbergmann/phploc/pull/182): `"continue" targeting switch is equivalent to "break". Did you mean to use "continue 2"`

### Removed

* This tool is no longer supported on PHP 5.6, PHP 7.0, and PHP 7.1

## [4.0.1] - 2017-11-18

### Changed

* This tool is now compatible with Symfony Console 4

## [4.0.0] - 2017-06-06

### Removed

* Removed the '--git-repository' option (and the corresponding functionality)
* Removed the '--progress' option (and the corresponding functionality)

## [3.0.1] - 2016-04-25

### Fixed

* Fixed [#139](https://github.com/sebastianbergmann/phploc/issues/139): Introduction of `T_USE` in `Analyser.php` gives `PHP Notice: Undefined index: ccn`
* Fixed [#141](https://github.com/sebastianbergmann/phploc/issues/141): `Undefined index: ccn in phar:///usr/local/bin/phploc/src/Analyser.php on line 507`

### Fixed

## [3.0.0] - 2016-01-13

[6.0.2]: https://github.com/sebastianbergmann/phploc/compare/6.0.1...6.0.2
[6.0.1]: https://github.com/sebastianbergmann/phploc/compare/6.0.0...6.0.1
[6.0.0]: https://github.com/sebastianbergmann/phploc/compare/5.0.0...6.0.0
[5.0.0]: https://github.com/sebastianbergmann/phploc/compare/4.0.1...5.0.0
[4.0.1]: https://github.com/sebastianbergmann/phploc/compare/4.0.0...4.0.1
[4.0.0]: https://github.com/sebastianbergmann/phploc/compare/3.0...4.0.0
[3.0.1]: https://github.com/sebastianbergmann/phploc/compare/3.0.0...3.0.1
[3.0.0]: https://github.com/sebastianbergmann/phploc/compare/2.1.5...3.0.0

