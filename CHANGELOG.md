# Change Log for PHPLOC
All notable changes to PHPLOC are documented in this file
using the [Keep a CHANGELOG](http://keepachangelog.com/) principles.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][unreleased]
[unreleased]: https://github.com/sebastianbergmann/phploc/compare/2.1.3...HEAD

### Changed
- Drop support for PHP < 5.4

### Fixed
- Make installable on php 7

## [2.1.3] - 2015-06-04
[2.1.3]: https://github.com/sebastianbergmann/phploc/compare/2.1.2...2.1.3

### Fixed
- PHPLOC treads ordinary comments differently than PHPdoc block comments

## [2.1.2] - 2015-05-26
[2.1.2]: https://github.com/sebastianbergmann/phploc/compare/2.1.1...2.1.2

### Fixed
- Undefined index: lloc in Analyser.php

## [2.1.1] - 2015-04-12
[2.1.1]: https://github.com/sebastianbergmann/phploc/compare/2.1.0...2.1.1

## [2.1.0] - 2015-03-10
[2.1.0]: https://github.com/sebastianbergmann/phploc/compare/2.0.6...2.1.0

### Changed
- Refactor how average complexity is calculated and implement minimum/maximum measures

### Fixed
- PHPLOC uses deprecated functionality

## [2.0.6] - 2014-06-25
[2.0.6]: https://github.com/sebastianbergmann/phploc/compare/2.0.5...2.0.6

### Changed
- Update composer.phar when it is older than 30 days
- Use ~ operator

## [2.0.5] - 2014-04-27
[2.0.5]: https://github.com/sebastianbergmann/phploc/compare/2.0.4...2.0.5

### Fixed
- Fix infinite loop in `isTestClass`

## [2.0.4] - 2013-12-18
[2.0.4]: https://github.com/sebastianbergmann/phploc/compare/2.0.3...2.0.4

### Fixed
- Non-Static method is counted as static when declared after declaration of static attribute

## [2.0.3] - 2013-11-05
[2.0.3]: https://github.com/sebastianbergmann/phploc/compare/2.0.2...2.0.3

### Added
- Added metrics to CSV output

### Changed
- Update installation instructions

## [2.0.2] - 2013-09-04
[2.0.2]: https://github.com/sebastianbergmann/phploc/compare/2.0.1...2.0.2

## [2.0.1] - 2013-09-03
[2.0.1]: https://github.com/sebastianbergmann/phploc/compare/2.0.0...2.0.1

### Fixed
- CLOC are counted twice

## [2.0.0] - 2013-08-28
[2.0.0]: https://github.com/sebastianbergmann/phploc/compare/2.0.0-BETA1...2.0.0

### Added
- Implement --log-json to write data in JSON format

## [2.0.0-BETA1] - 2013-08-02
[2.0.0-BETA1]: https://github.com/sebastianbergmann/phploc/compare/1.7.4...2.0.0-BETA1

### Changed
- Ignore .idea directory

## [1.7.4] - 2012-11-10
[1.7.4]: https://github.com/sebastianbergmann/phploc/compare/1.7.3...1.7.4

### Fixed
- Fixed invalid bin reference in composer.json

## [1.7.3] - 2012-11-09
[1.7.3]: https://github.com/sebastianbergmann/phploc/compare/1.7.2...1.7.3

### Added
-  Adding composer definition and a binary which includes composer autoloader

## [1.7.2] - 2012-10-11
[1.7.2]: https://github.com/sebastianbergmann/phploc/compare/1.7.1...1.7.2

### Fixed
- Fixed namespace issue

## [1.7.1] - 2012-10-10
[1.7.1]: https://github.com/sebastianbergmann/phploc/compare/1.7.0...1.7.1

### Added
- Add PHAR generation task

## [1.7.0] - 2012-10-09
[1.7.0]: https://github.com/sebastianbergmann/phploc/compare/1.6.4...1.7.0

### Added
- Add support for traits
- Add build system

### Changed
- Rename --verbose to --progress

## [1.6.4] - 2011-11-17
[1.6.4]: https://github.com/sebastianbergmann/phploc/compare/1.6.3...1.6.4

### Fixed
- Fix handling of suffixes

## [1.6.3] - 2011-11-16
[1.6.3]: https://github.com/sebastianbergmann/phploc/compare/1.6.2...1.6.3

### Fixed
-  	Fix handling of exclude paths

## [1.6.2] - 2011-10-31
[1.6.2]: https://github.com/sebastianbergmann/phploc/compare/1.6.2RC1...1.6.2

### Fixed
-  Fixed .bat file to correctly set environment variable without double quotes

## [1.6.2RC1] - 2011-09-05
[1.6.2RC1]: https://github.com/sebastianbergmann/phploc/compare/1.6.1...1.6.2RC1

### Changed
- Bump PEAR requirement

## [1.6.1] - 2011-01-28
[1.6.1]: https://github.com/sebastianbergmann/phploc/compare/1.6.0...1.6.1

### Changed
- Print pretty column titles

## [1.6.0] - 2011-01-27
[1.6.0]: https://github.com/sebastianbergmann/phploc/compare/1.5.1...1.6.0

#### Added
- Added a CSV printer

## [1.5.1] - 2010-02-09
[1.5.1]: https://github.com/sebastianbergmann/phploc/compare/1.5.0...1.5.1

## [1.5.0] - 2010-01-03
[1.5.0]: https://github.com/sebastianbergmann/phploc/compare/1.4.0...1.5.0

### Fixed
- Fix detection of anonymous functions
- Use NCLOC to calculate for average class/method length

## [1.4.0] - 2009-11-24
[1.4.0]: https://github.com/sebastianbergmann/phploc/compare/1.3.2...1.4.0

### Added
- Count namespaces
- Count anonymous functions

## [1.3.2] - 2009-11-12
[1.3.2]: https://github.com/sebastianbergmann/phploc/compare/1.3.1...1.3.2

### Changed
- Use File_Iterator_Factory::getFilesAsArray()

## [1.3.1] - 2009-11-06
[1.3.1]: https://github.com/sebastianbergmann/phploc/compare/1.3.0...1.3.1

### Changed
-  	Use File_Iterator

## [1.3.0] - 2009-11-04
[1.3.0]: https://github.com/sebastianbergmann/phploc/compare/1.2.0...1.3.0

### Added
- Implemented LOC/NOC and LOC/NOM metrics
- Implement CCN/LOC and CCN/NOM metrics
-  Count public and non-public methods

## [1.2.0] - 2009-09-11
[1.2.0]: https://github.com/sebastianbergmann/phploc/compare/1.1.1...1.2.0

## [1.1.1] - 2009-06-03
[1.1.1]: https://github.com/sebastianbergmann/phploc/compare/1.1.0...1.1.1

## [1.1.0] - 2009-06-02
[1.1.0]: https://github.com/sebastianbergmann/phploc/compare/1.0.0...1.1.0

## [1.0.0] - 2009-01-26
[1.0.0]: https://github.com/sebastianbergmann/phploc/compare/8feb7ea5c50a8754a44952fce1defafd86b64ea5...1.0.0

### Added
- Initial functionality

