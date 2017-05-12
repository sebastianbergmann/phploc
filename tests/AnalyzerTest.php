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

class AnalyserTest extends TestCase
{
    /**
     * @var Analyser
     */
    private $analyser;

    protected function setUp()
    {
        $this->analyser = new Analyser;
    }

    public function testWithoutTests()
    {
        $this->assertEquals(
            [
                'files'                       => 1,
                'loc'                         => 75,
                'lloc'                        => 26,
                'llocClasses'                 => 22,
                'llocFunctions'               => 1,
                'llocGlobal'                  => 3,
                'cloc'                        => 7,
                'ccn'                         => 2,
                'ccnMethods'                  => 2,
                'interfaces'                  => 1,
                'traits'                      => 0,
                'classes'                     => 2,
                'abstractClasses'             => 1,
                'concreteClasses'             => 1,
                'functions'                   => 2,
                'namedFunctions'              => 1,
                'anonymousFunctions'          => 1,
                'methods'                     => 4,
                'publicMethods'               => 2,
                'nonPublicMethods'            => 2,
                'nonStaticMethods'            => 3,
                'staticMethods'               => 1,
                'constants'                   => 2,
                'classConstants'              => 1,
                'globalConstants'             => 1,
                'testClasses'                 => 0,
                'testMethods'                 => 0,
                'ccnByLloc'                   => 0.08,
                'llocByNof'                   => 0.5,
                'methodCalls'                 => 6,
                'staticMethodCalls'           => 4,
                'instanceMethodCalls'         => 2,
                'attributeAccesses'           => 6,
                'staticAttributeAccesses'     => 4,
                'instanceAttributeAccesses'   => 2,
                'globalAccesses'              => 4,
                'globalVariableAccesses'      => 2,
                'superGlobalVariableAccesses' => 1,
                'globalConstantAccesses'      => 1,
                'directories'                 => 0,
                'namespaces'                  => 1,
                'ncloc'                       => 68,
                'classCcnMin'                 => 1,
                'classCcnAvg'                 => 1.65,
                'classCcnMax'                 => 3,
                'methodCcnMin'                => 1,
                'methodCcnAvg'                => 1.65,
                'methodCcnMax'                => 2,
                'classLlocMin'                => 0,
                'classLlocAvg'                => 7.3,
                'classLlocMax'                => 22,
                'methodLlocMin'               => 4,
                'methodLlocAvg'               => 5.6,
                'methodLlocMax'               => 7
            ],
            $this->analyser->countFiles(
                [__DIR__ . '/_files/source.php'],
                false
            ),
            '',
            0.1
        );
    }

    public function testWithTests()
    {
        $this->assertEquals(
            [
                'files'                       => 2,
                'loc'                         => 98,
                'lloc'                        => 26,
                'llocClasses'                 => 22,
                'llocFunctions'               => 1,
                'llocGlobal'                  => 3,
                'cloc'                        => 11,
                'ccn'                         => 2,
                'ccnMethods'                  => 2,
                'interfaces'                  => 1,
                'traits'                      => 0,
                'classes'                     => 2,
                'abstractClasses'             => 1,
                'concreteClasses'             => 1,
                'functions'                   => 2,
                'namedFunctions'              => 1,
                'anonymousFunctions'          => 1,
                'methods'                     => 4,
                'publicMethods'               => 2,
                'nonPublicMethods'            => 2,
                'nonStaticMethods'            => 3,
                'staticMethods'               => 1,
                'constants'                   => 2,
                'classConstants'              => 1,
                'globalConstants'             => 1,
                'testClasses'                 => 1,
                'testMethods'                 => 2,
                'ccnByLloc'                   => 0.08,
                'llocByNof'                   => 0.5,
                'methodCalls'                 => 6,
                'staticMethodCalls'           => 4,
                'instanceMethodCalls'         => 2,
                'attributeAccesses'           => 6,
                'staticAttributeAccesses'     => 4,
                'instanceAttributeAccesses'   => 2,
                'globalAccesses'              => 4,
                'globalVariableAccesses'      => 2,
                'superGlobalVariableAccesses' => 1,
                'globalConstantAccesses'      => 1,
                'directories'                 => 0,
                'namespaces'                  => 1,
                'ncloc'                       => 87,
                'classCcnMin'                 => 1,
                'classCcnAvg'                 => 1.5,
                'classCcnMax'                 => 3,
                'methodCcnMin'                => 1,
                'methodCcnAvg'                => 1.66,
                'methodCcnMax'                => 2,
                'classLlocMin'                => 0,
                'classLlocAvg'                => 5.5,
                'classLlocMax'                => 22,
                'methodLlocMin'               => 4,
                'methodLlocAvg'               => 5.6,
                'methodLlocMax'               => 7
            ],
            $this->analyser->countFiles(
                [
                    __DIR__ . '/_files/source.php',
                    __DIR__ . '/_files/tests.php'
                ],
                true
            ),
            '',
            0.1
        );
    }

    public function testFilesThatExtendPHPUnitTestCaseAreCountedAsTests()
    {
        $result = $this->analyser->countFiles(
            [
                __DIR__ . '/_files/tests.php'
            ],
            true
        );

        $this->assertEquals(1, $result['testClasses']);
    }

    public function testFilesThatExtendPHPUnitTestCaseAreCountedAsTests2()
    {
        $result = $this->analyser->countFiles(
            [
                __DIR__ . '/_files/tests_old.php'
            ],
            true
        );

        $this->assertEquals(1, $result['testClasses']);
    }

    public function testFilesThatIndirectlyExtendPHPUnitTestCaseAreCountedAsTests()
    {
        $result = $this->analyser->countFiles(
            [
                __DIR__ . '/_files/twoTestsThatIndirectlyExtendOldPHPUnitTestCase.php'
            ],
            true
        );

        $this->assertEquals(3, $result['testClasses']);
    }

    public function testFilesThatIndirectlyExtendPHPUnitTestCaseAreCountedAsTests2()
    {
        $result = $this->analyser->countFiles(
            [
                __DIR__ . '/_files/twoTestsThatIndirectlyExtendPHPUnitTestCase.php'
            ],
            true
        );

        $this->assertEquals(3, $result['testClasses']);
    }

    public function testTraitsAreCountedCorrectly()
    {
        $result = $this->analyser->countFiles(
            [
                __DIR__ . '/_files/trait.php'
            ],
            false
        );

        $this->assertEquals(1, $result['traits']);
    }

    /**
     * @ticket 64
     */
    public function testIssue64IsFixed()
    {
        $result = $this->analyser->countFiles(
            [
                __DIR__ . '/_files/issue_62.php'
            ],
            false
        );

        $this->assertEquals(1, $result['cloc']);
    }

    /**
     * @ticket 112
     */
    public function testIssue112IsFixed()
    {
        $result = $this->analyser->countFiles(
            [
                __DIR__ . '/_files/issue_112.php'
            ],
            false
        );

        $this->assertEquals(5, $result['loc']);
    }

    /**
     * @ticket 126
     * @dataProvider issue126Provider
     */
    public function testIssue126IsFixed($fileNumber, $cloc)
    {
        $file   = __DIR__ . '/_files/issue_126/issue_126_' . $fileNumber . '.php';
        $result = $this->analyser->countFiles([$file], false);

        $assertString = \sprintf('Failed asserting that %s matches expected %s in issue_126_%d.php',
                            $result['cloc'],
                            $cloc,
                            $fileNumber
        );

        $this->assertEquals($cloc, $result['cloc'], $assertString);
    }

    public function issue126Provider()
    {
        // issue_126_X.php => CLOC
        return [
            [1, 1],
            [2, 1],
            [3, 1],
            [4, 2],
            [5, 3],
            [6, 3],
            [7, 3],
        ];
    }

    /**
     * @requires PHP 7
     * @ticket 138
     */
    public function testIssue138IsFixed()
    {
        \error_reporting(E_ALL);

        $result = $this->analyser->countFiles(
            [
                __DIR__ . '/_files/issue_138.php'
            ],
            false
        );

        $this->assertSame(1, $result['classes']);
    }

    /**
     * @ticket 139
     */
    public function testIssue139IsFixed()
    {
        \error_reporting(E_ALL);

        $result = $this->analyser->countFiles(
            [
                __DIR__ . '/_files/issue_139.php'
            ],
            false
        );

        $this->assertEquals(1, $result['anonymousFunctions']);
    }
}
