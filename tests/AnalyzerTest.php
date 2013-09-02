<?php
/**
 * PHPLOC
 *
 * Copyright (c) 2009-2013, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    PHPLOC
 * @subpackage Tests
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2009-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @since      File available since Release 1.3.0
 */

/**
 * Tests for the PHPLOC_Analyser class.
 *
 * @package    PHPLOC
 * @subpackage Tests
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2009-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://github.com/sebastianbergmann/phploc/
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
        $expected =
          array(
            'files' => 1,
            'loc' => 68,
            'cloc' => 3,
            'ncloc' => 65,
            'ccn' => 2,
            'ccnMethods' => 2,
            'interfaces' => 1,
            'classes' => 2,
            'abstractClasses' => 1,
            'concreteClasses' => 1,
            'anonymousFunctions' => 1,
            'functions' => 2,
            'methods' => 4,
            'publicMethods' => 2,
            'nonPublicMethods' => 2,
            'nonStaticMethods' => 3,
            'staticMethods' => 1,
            'constants' => 2,
            'classConstants' => 1,
            'globalConstants' => 1,
            'ccnByNom' => 1.5,
            'directories' => 0,
            'namespaces' => 1,
            'traits' => 0,
            'testClasses' => 0,
            'testMethods' => 0,
            'methodCalls' => 6,
            'staticMethodCalls' => 4,
            'instanceMethodCalls' => 2,
            'attributeAccesses' => 6,
            'staticAttributeAccesses' => 4,
            'instanceAttributeAccesses' => 2,
            'lloc' => 24,
            'llocClasses' => 21,
            'namedFunctions' => 1,
            'ccnByLloc' => 0.08,
            'llocByNoc' => 10.5,
            'llocByNom' => 5.25,
            'llocFunctions' => 1,
            'llocGlobal' => 2,
            'llocByNof' => 0.5,
            'globalAccesses' => 4,
            'globalVariableAccesses' => 2,
            'superGlobalVariableAccesses' => 1,
            'globalConstantAccesses' => 1
        );

        $this->assertEquals(
          $expected,
          $this->analyser->countFiles(
            array(__DIR__ . '/_files/source.php'), FALSE
          ),
          '',
          0.01
        );
    }

    public function testWithTests()
    {
        $expected =
          array(
            'files' => 2,
            'loc' => 91,
            'cloc' => 7,
            'ncloc' => 84,
            'ccn' => 2,
            'ccnMethods' => 2,
            'interfaces' => 1,
            'classes' => 2,
            'abstractClasses' => 1,
            'concreteClasses' => 1,
            'anonymousFunctions' => 1,
            'functions' => 2,
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
            'ccnByNom' => 1.5,
            'directories' => 0,
            'namespaces' => 1,
            'traits' => 0,
            'methodCalls' => 6,
            'staticMethodCalls' => 4,
            'instanceMethodCalls' => 2,
            'attributeAccesses' => 6,
            'staticAttributeAccesses' => 4,
            'instanceAttributeAccesses' => 2,
            'lloc' => 24,
            'llocClasses' => 21,
            'namedFunctions' => 1,
            'ccnByLloc' => 0.08,
            'llocByNoc' => 10.5,
            'llocByNom' => 5.25,
            'llocFunctions' => 1,
            'llocGlobal' => 2,
            'llocByNof' => 0.5,
            'globalAccesses' => 4,
            'globalVariableAccesses' => 2,
            'superGlobalVariableAccesses' => 1,
            'globalConstantAccesses' => 1
        );

        $this->assertEquals(
          $expected,
          $this->analyser->countFiles(
            array(
              __DIR__ . '/_files/source.php',
              __DIR__ . '/_files/tests.php'
            ), TRUE
          ),
          '',
          0.01
        );
    }

    public function testFilesThatExtendPHPUnitTestCaseAreCountedAsTests() {
        $result = $this->analyser->countFiles(
          array(
            __DIR__ . '/_files/tests.php'
          ),
          TRUE
        );

        $this->assertEquals(1, $result['testClasses']);
    }

    public function testFilesThatIndirectlyExtendPHPUnitTestCaseAreCountedAsTests() {
        $result = $this->analyser->countFiles(
          array(
            __DIR__ . '/_files/twoTestsThatIndirectlyExtendPHPUnitTestCase.php'
          ),
          TRUE
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
          FALSE
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
          FALSE
        );

        $this->assertEquals(1, $result['cloc']);
    }
}
