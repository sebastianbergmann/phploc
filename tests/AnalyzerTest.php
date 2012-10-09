<?php
/**
 * PHPLOC
 *
 * Copyright (c) 2009-2012, Sebastian Bergmann <sb@sebastian-bergmann.de>.
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
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2009-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @since      File available since Release 1.3.0
 */

/**
 * Tests for the PHPLOC_Analyser class.
 *
 * @package    PHPLOC
 * @subpackage Tests
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2009-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://github.com/sebastianbergmann/phploc/
 * @since      Class available since Release 1.3.0
 */
class PHPLOC_AnalyserTest extends PHPUnit_Framework_TestCase
{
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
            'loc' => 47,
            'nclocClasses' => 29,
            'cloc' => 3,
            'ncloc' => 44,
            'eloc' => 23,
            'ccn' => 2,
            'ccnMethods' => 2,
            'interfaces' => 1,
            'classes' => 2,
            'abstractClasses' => 1,
            'concreteClasses' => 1,
            'anonymousFunctions' => 1,
            'functions' => 1,
            'methods' => 4,
            'publicMethods' => 2,
            'nonPublicMethods' => 2,
            'nonStaticMethods' => 3,
            'staticMethods' => 1,
            'constants' => 2,
            'classConstants' => 1,
            'globalConstants' => 1,
            'ccnByLoc' => 0.045,
            'ccnByNom' => 1.5,
            'nclocByNoc' => 14.5,
            'nclocByNom' => 7.25,
            'directories' => 0,
            'namespaces' => 1,
            'traits' => 0
        );
        if(!extension_loaded('bytekit')) {
          unset($expected['eloc']);
        }
        $this->assertEquals(
          $expected,
          $this->analyser->countFiles(
            array($this->getFileObject('source.php')), FALSE
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
            'loc' => 58,
            'nclocClasses' => 38,
            'cloc' => 3,
            'ncloc' => 55,
            'eloc' => 28,
            'ccn' => 2,
            'ccnMethods' => 2,
            'interfaces' => 1,
            'classes' => 2,
            'abstractClasses' => 1,
            'concreteClasses' => 1,
            'anonymousFunctions' => 1,
            'functions' => 1,
            'methods' => 4,
            'publicMethods' => 2,
            'nonPublicMethods' => 2,
            'nonStaticMethods' => 3,
            'staticMethods' => 1,
            'constants' => 2,
            'classConstants' => 1,
            'globalConstants' => 1,
            'testClasses' => 1,
            'testMethods' => 1,
            'ccnByLoc' => 0.036,
            'ccnByNom' => 1.666,
            'nclocByNoc' => 19,
            'nclocByNom' => 9.5,
            'directories' => 0,
            'namespaces' => 1,
            'traits' => 0
        );
        if(!extension_loaded('bytekit')) {
          unset($expected['eloc']);
        }
        $this->assertEquals(
          $expected,
          $this->analyser->countFiles(
            array(
              $this->getFileObject('source.php'),
              $this->getFileObject('tests.php')
            ), TRUE
          ),
          '',
          0.01
        );
    }

    public function testFilesThatExtendPHPUnitTestCaseAreCountedAsTests() {
        $result = $this->analyser->countFiles(
          array(
            $this->getFileObject('tests.php')
          ), TRUE
        );
        $this->assertSame(1, $result['testClasses']);
    }

    public function testFilesThatIndirectlyExtendPHPUnitTestCaseAreCountedAsTests() {
        $result = $this->analyser->countFiles(
            array(
                $this->getFileObject('twoTestsThatIndirectlyExtendPHPUnitTestCase.php')
            ), TRUE
        );
        $this->assertSame(3, $result['testClasses']);
    }

    protected function getFileObject($filename)
    {
        return new SplFileObject(
          dirname(__FILE__) . DIRECTORY_SEPARATOR .
          '_files' . DIRECTORY_SEPARATOR . $filename
        );
    }
}
