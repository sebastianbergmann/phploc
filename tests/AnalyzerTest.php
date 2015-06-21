<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Tests for the PHPLOC_Analyser class.
 *
 * @since      Class available since Release 1.3.0
 */
class PHPLOC_AnalyserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SebastianBergmann\PHPLOC\Analyser
     */
    protected $analyser;

    protected function setUp()
    {
        $this->analyser = new SebastianBergmann\PHPLOC\Analyser;
    }

    public function testWithoutTests()
    {
        $this->assertEquals(
            array(
                'files' => 1,
                'loc' => 73,
                'lloc' => 25,
                'llocClasses' => 22,
                'llocFunctions' => 1,
                'llocGlobal' => 2,
                'cloc' => 7,
                'ccn' => 2,
                'ccnMethods' => 2,
                'interfaces' => 1,
                'traits' => 0,
                'classes' => 2,
                'abstractClasses' => 1,
                'concreteClasses' => 1,
                'functions' => 2,
                'namedFunctions' => 1,
                'anonymousFunctions' => 1,
                'methods' => 4,
                'publicMethods' => 2,
                'nonPublicMethods' => 2,
                'nonStaticMethods' => 3,
                'staticMethods' => 1,
                'constants' => 2,
                'classConstants' => 1,
                'globalConstants' => 1,
                'testClasses' => 0,
                'testMethods' => 0,
                'ccnByLloc' => 0.08,
                'llocByNof' => 0.5,
                'methodCalls' => 6,
                'staticMethodCalls' => 4,
                'instanceMethodCalls' => 2,
                'attributeAccesses' => 6,
                'staticAttributeAccesses' => 4,
                'instanceAttributeAccesses' => 2,
                'globalAccesses' => 4,
                'globalVariableAccesses' => 2,
                'superGlobalVariableAccesses' => 1,
                'globalConstantAccesses' => 1,
                'directories' => 0,
                'namespaces' => 1,
                'ncloc' => 66,
                'classCcnMin' => 1,
                'classCcnAvg' => 1.65,
                'classCcnMax' => 3,
                'methodCcnMin' => 1,
                'methodCcnAvg' => 1.65,
                'methodCcnMax' => 2,
                'classLlocMin' => 0,
                'classLlocAvg' => 7.3,
                'classLlocMax' => 22,
                'methodLlocMin' => 4,
                'methodLlocAvg' => 5.6,
                'methodLlocMax' => 7
            ),
            $this->analyser->countFiles(
                array(__DIR__ . '/_files/source.php'),
                false
            ),
            '',
            0.1
        );
    }

    public function testWithTests()
    {
        $this->assertEquals(
            array(
                'files' => 2,
                'loc' => 96,
                'lloc' => 25,
                'llocClasses' => 22,
                'llocFunctions' => 1,
                'llocGlobal' => 2,
                'cloc' => 11,
                'ccn' => 2,
                'ccnMethods' => 2,
                'interfaces' => 1,
                'traits' => 0,
                'classes' => 2,
                'abstractClasses' => 1,
                'concreteClasses' => 1,
                'functions' => 2,
                'namedFunctions' => 1,
                'anonymousFunctions' => 1,
                'methods' => 4,
                'publicMethods' => 2,
                'nonPublicMethods' => 2,
                'nonStaticMethods' => 3,
                'staticMethods' => 1,
                'constants' => 2,
                'classConstants' => 1,
                'globalConstants' => 1,
                'testClasses' => 1,
                'testMethods' => 2,
                'ccnByLloc' => 0.08,
                'llocByNof' => 0.5,
                'methodCalls' => 6,
                'staticMethodCalls' => 4,
                'instanceMethodCalls' => 2,
                'attributeAccesses' => 6,
                'staticAttributeAccesses' => 4,
                'instanceAttributeAccesses' => 2,
                'globalAccesses' => 4,
                'globalVariableAccesses' => 2,
                'superGlobalVariableAccesses' => 1,
                'globalConstantAccesses' => 1,
                'directories' => 0,
                'namespaces' => 1,
                'ncloc' => 85,
                'classCcnMin' => 1,
                'classCcnAvg' => 1.5,
                'classCcnMax' => 3,
                'methodCcnMin' => 1,
                'methodCcnAvg' => 1.66,
                'methodCcnMax' => 2,
                'classLlocMin' => 0,
                'classLlocAvg' => 5.5,
                'classLlocMax' => 22,
                'methodLlocMin' => 4,
                'methodLlocAvg' => 5.6,
                'methodLlocMax' => 7
            ),
            $this->analyser->countFiles(
                array(
                    __DIR__ . '/_files/source.php',
                    __DIR__ . '/_files/tests.php'
                ),
                true
            ),
            '',
            0.1
        );
    }

    public function testFilesThatExtendPHPUnitTestCaseAreCountedAsTests() {
        $result = $this->analyser->countFiles(
            array(
                __DIR__ . '/_files/tests.php'
            ),
            true
        );

        $this->assertEquals(1, $result['testClasses']);
    }

    public function testFilesThatIndirectlyExtendPHPUnitTestCaseAreCountedAsTests() {
        $result = $this->analyser->countFiles(
            array(
                __DIR__ . '/_files/twoTestsThatIndirectlyExtendPHPUnitTestCase.php'
            ),
            true
        );

        $this->assertEquals(3, $result['testClasses']);
    }

    /**
     * @requires PHP 5.4
     */
    public function testTraitsAreCountedCorrectly()
    {
        $result = $this->analyser->countFiles(
            array(
                __DIR__ . '/_files/trait.php'
            ),
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
            array(
                __DIR__ . '/_files/issue_62.php'
            ),
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
            array(
                __DIR__ . '/_files/issue_112.php'
            ),
            false
        );

        $this->assertEquals(5, $result['loc']);
    }
}
