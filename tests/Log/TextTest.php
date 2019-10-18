<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\PHPLOC;

use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    /**
     * @var \SebastianBergmann\PHPLOC\Log\Xml
     */
    private $single;

    private $sample_row = [
        'directories'                 => 1,
        'files'                       => 2,
        'loc'                         => 3,
        'ccnByLloc'                   => 4,
        'cloc'                        => 5,
        'ncloc'                       => 6,
        'lloc'                        => 7,
        'llocGlobal'                  => 8,
        'namespaces'                  => 9,
        'interfaces'                  => 10,
        'traits'                      => 11,
        'classes'                     => 12,
        'abstractClasses'             => 13,
        'concreteClasses'             => 14,
        'llocClasses'                 => 15,
        'methods'                     => 16,
        'nonStaticMethods'            => 17,
        'staticMethods'               => 18,
        'publicMethods'               => 19,
        'nonPublicMethods'            => 20,
        'methodCcnAvg'                => 21,
        'functions'                   => 22,
        'namedFunctions'              => 23,
        'anonymousFunctions'          => 24,
        'llocFunctions'               => 25,
        'llocByNof'                   => 26,
        'constants'                   => 27,
        'globalConstants'             => 28,
        'classConstants'              => 29,
        'attributeAccesses'           => 30,
        'instanceAttributeAccesses'   => 31,
        'staticAttributeAccesses'     => 32,
        'methodCalls'                 => 33,
        'instanceMethodCalls'         => 34,
        'staticMethodCalls'           => 35,
        'globalAccesses'              => 36,
        'globalVariableAccesses'      => 37,
        'superGlobalVariableAccesses' => 38,
        'globalConstantAccesses'      => 39,
        'testClasses'                 => 40,
        'testMethods'                 => 41,
        'classCcnAvg'                 => 42,
        'classLlocAvg'                => 43,
        'methodLlocAvg'               => 44,
        'classLlocMin'                => 45,
        'classLlocMax'                => 46,
        'methodLlocMin'               => 47,
        'methodLlocMax'               => 48,
        'classCcnMin'                 => 49,
        'classCcnMax'                 => 50,
        'methodCcnMin'                => 51,
        'methodCcnMax'                => 52,
    ];

    protected function setUp(): void
    {
        $this->single = new \SebastianBergmann\PHPLOC\Log\Text;
    }

    public function testPrintedResult(): void
    {
        $output = new \Symfony\Component\Console\Output\BufferedOutput;
        $this->single->printResult($output, $this->sample_row, false);

        $expected = <<<END
Directories                                          1
Files                                                2

Size
  Lines of Code (LOC)                                3
  Comment Lines of Code (CLOC)                       5 (166.67%)
  Non-Comment Lines of Code (NCLOC)                  6 (200.00%)
  Logical Lines of Code (LLOC)                       7 (233.33%)
    Classes                                         15 (214.29%)
      Average Class Length                          43
        Minimum Class Length                        45
        Maximum Class Length                        46
      Average Method Length                         44
        Minimum Method Length                       47
        Maximum Method Length                       48
    Functions                                       25 (357.14%)
      Average Function Length                       26
    Not in classes or functions                      8 (114.29%)

Cyclomatic Complexity
  Average Complexity per LLOC                     4.00
  Average Complexity per Class                   42.00
    Minimum Class Complexity                     49.00
    Maximum Class Complexity                     50.00
  Average Complexity per Method                  21.00
    Minimum Method Complexity                    51.00
    Maximum Method Complexity                    52.00

Dependencies
  Global Accesses                                   36
    Global Constants                                39 (108.33%)
    Global Variables                                37 (102.78%)
    Super-Global Variables                          38 (105.56%)
  Attribute Accesses                                30
    Non-Static                                      31 (103.33%)
    Static                                          32 (106.67%)
  Method Calls                                      33
    Non-Static                                      34 (103.03%)
    Static                                          35 (106.06%)

Structure
  Namespaces                                         9
  Interfaces                                        10
  Traits                                            11
  Classes                                           12
    Abstract Classes                                13 (108.33%)
    Concrete Classes                                14 (116.67%)
  Methods                                           16
    Scope
      Non-Static Methods                            17 (106.25%)
      Static Methods                                18 (112.50%)
    Visibility
      Public Methods                                19 (118.75%)
      Non-Public Methods                            20 (125.00%)
  Functions                                         22
    Named Functions                                 23 (104.55%)
    Anonymous Functions                             24 (109.09%)
  Constants                                         27
    Global Constants                                28 (103.70%)
    Class Constants                                 29 (107.41%)
END;

        $this->assertEquals(\trim($expected), \trim($output->fetch()));
    }

    public function testPrintedResultWithNoSubDirectories(): void
    {
        $output = new \Symfony\Component\Console\Output\BufferedOutput;
        $this->single->printResult($output, array_merge($this->sample_row, ['directories' => 0]), false);

        $expected = <<<END
Directories                                          0
Files                                                2

Size
  Lines of Code (LOC)                                3
  Comment Lines of Code (CLOC)                       5 (166.67%)
  Non-Comment Lines of Code (NCLOC)                  6 (200.00%)
  Logical Lines of Code (LLOC)                       7 (233.33%)
    Classes                                         15 (214.29%)
      Average Class Length                          43
        Minimum Class Length                        45
        Maximum Class Length                        46
      Average Method Length                         44
        Minimum Method Length                       47
        Maximum Method Length                       48
    Functions                                       25 (357.14%)
      Average Function Length                       26
    Not in classes or functions                      8 (114.29%)

Cyclomatic Complexity
  Average Complexity per LLOC                     4.00
  Average Complexity per Class                   42.00
    Minimum Class Complexity                     49.00
    Maximum Class Complexity                     50.00
  Average Complexity per Method                  21.00
    Minimum Method Complexity                    51.00
    Maximum Method Complexity                    52.00

Dependencies
  Global Accesses                                   36
    Global Constants                                39 (108.33%)
    Global Variables                                37 (102.78%)
    Super-Global Variables                          38 (105.56%)
  Attribute Accesses                                30
    Non-Static                                      31 (103.33%)
    Static                                          32 (106.67%)
  Method Calls                                      33
    Non-Static                                      34 (103.03%)
    Static                                          35 (106.06%)

Structure
  Namespaces                                         9
  Interfaces                                        10
  Traits                                            11
  Classes                                           12
    Abstract Classes                                13 (108.33%)
    Concrete Classes                                14 (116.67%)
  Methods                                           16
    Scope
      Non-Static Methods                            17 (106.25%)
      Static Methods                                18 (112.50%)
    Visibility
      Public Methods                                19 (118.75%)
      Non-Public Methods                            20 (125.00%)
  Functions                                         22
    Named Functions                                 23 (104.55%)
    Anonymous Functions                             24 (109.09%)
  Constants                                         27
    Global Constants                                28 (103.70%)
    Class Constants                                 29 (107.41%)
END;

        $this->assertEquals(\trim($expected), \trim($output->fetch()));
    }

}
