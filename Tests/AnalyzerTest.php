<?php
/**
 * PHPLOC
 *
 * Copyright (c) 2009, Sebastian Bergmann <sb@sebastian-bergmann.de>.
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
 * @copyright  2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @since      File available since Release 1.3.0
 */

require_once 'PHPLOC/Analyser.php';

/**
 * Tests for the PHPLOC_Analyser class.
 *
 * @package    PHPLOC
 * @subpackage Tests
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://github.com/sebastianbergmann/phploc/
 * @since      Class available since Release 1.3.0
 */
class PHPLOC_AnalyserTest extends PHPUnit_Framework_TestCase
{
    protected $analyser;

    protected function setUp()
    {
        $this->analyser = new PHPLOC_Analyser;
    }

    public function testWithoutTests()
    {
        $this->assertEquals(
          array(
            'files' => 1,
            'loc' => 46,
            'locClasses' => 33,
            'cloc' => 3,
            'ncloc' => 43,
            'eloc' => 22,
            'ccn' => 2,
            'ccnMethods' => 2,
            'interfaces' => 1,
            'classes' => 2,
            'abstractClasses' => 1,
            'concreteClasses' => 1,
            'functions' => 1,
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
            'ccnByLoc' => 0.090909090909091,
            'ccnByNom' => 1.5,
            'locByNoc' => 16.5,
            'locByNom' => 8.25,
            'directories' => 0,
            'namespaces' => 1
          ),
          $this->analyser->countFiles(
            array($this->getFileObject('source.php')), FALSE
          ),
          '',
          0.01
        );
    }

    public function testWithTests()
    {
        $this->assertEquals(
          array(
            'files' => 2,
            'loc' => 57,
            'locClasses' => 43,
            'cloc' => 3,
            'ncloc' => 54,
            'eloc' => 27,
            'ccn' => 2,
            'ccnMethods' => 2,
            'interfaces' => 1,
            'classes' => 2,
            'abstractClasses' => 1,
            'concreteClasses' => 1,
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
            'ccnByLoc' => 0.07,
            'ccnByNom' => 1.5,
            'locByNoc' => 21.5,
            'locByNom' => 10.75,
            'directories' => 0,
            'namespaces' => 1
          ),
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

    protected function getFileObject($filename)
    {
        return new SplFileObject(
          dirname(__FILE__) . DIRECTORY_SEPARATOR .
          '_files' . DIRECTORY_SEPARATOR . $filename
        );
    }
}
