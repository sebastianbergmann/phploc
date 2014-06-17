<?php
/**
 * PHPLOC
 *
 * Copyright (c) 2009-2014, Sebastian Bergmann <sebastian@phpunit.de>.
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
 * @copyright  2009-2014 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */

class SingleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SebastianBergmann\PHPLOC\Log\CSV\Single
     */
    private $single;

    private $sample_row = array(
        'directories' => 1,
        'files' => 2,
        'loc' => 3,
        'ccnByLloc' => 4,
        'cloc' => 5,
        'ncloc' => 6,
        'lloc' => 7,
        'llocGlobal' => 8,
        'namespaces' => 9,
        'interfaces' => 10,
        'traits' => 11,
        'classes' => 12,
        'abstractClasses' => 13,
        'concreteClasses' => 14,
        'llocClasses' => 15,
        'methods' => 16,
        'nonStaticMethods' => 17,
        'staticMethods' => 18,
        'publicMethods' => 19,
        'nonPublicMethods' => 20,
        'methodCcnAvg' => 21,
        'functions' => 22,
        'namedFunctions' => 23,
        'anonymousFunctions' => 24,
        'llocFunctions' => 25,
        'llocByNof' => 26,
        'constants' => 27,
        'globalConstants' => 28,
        'classConstants' => 29,
        'attributeAccesses' => 30,
        'instanceAttributeAccesses' => 31,
        'staticAttributeAccesses' => 32,
        'methodCalls' => 33,
        'instanceMethodCalls' => 34,
        'staticMethodCalls' => 35,
        'globalAccesses' => 36,
        'globalVariableAccesses' => 37,
        'superGlobalVariableAccesses' => 38,
        'globalConstantAccesses' => 39,
        'testClasses' => 40,
        'testMethods' => 41
    );

    public function setUp()
    {
        $this->single = new \SebastianBergmann\PHPLOC\Log\CSV\Single();
    }

    public function testPrintedResultContainsHeadings()
    {
        ob_start();
        $this->single->printResult('php://output', $this->sample_row);
        $output = ob_get_clean();

        $this->assertRegExp('#Directories,Files.+$#is', $output, "Printed result does not contain a heading line");
    }

    public function testPrintedResultContainsData()
    {
        ob_start();
        $this->single->printResult('php://output', $this->sample_row);
        $output = ob_get_clean();

        $this->assertRegExp('#"1","2".+$#is', $output, "Printed result does not contain a value line");
    }

    public function testPrintedResultContainsEqualNumHeadingsAndValues()
    {
        ob_start();
        $this->single->printResult('php://output', $this->sample_row);
        $output = ob_get_clean();

        $rows = explode("\n", $output);
        $headings = explode(",", $rows[0]);
        $vals = explode(",", $rows[1]);

        $this->assertEquals(
            count($headings),
            count($vals),
            "Printed result does not contain same number of headings and values"
        );
    }

    public function testExactlyTwoRowsArePrinted()
    {
        ob_start();
        $this->single->printResult('php://output', $this->sample_row);
        $output = ob_get_clean();

        $rows = explode("\n", trim($output));
        $this->assertEquals(2, count($rows), "Printed result contained more or less than expected 2 rows");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPrintPartialRow()
    {
        $count = $this->sample_row;
        unset($count['llocByNof']);

        ob_start();
        $this->single->printResult('php://output', $count);
        ob_end_clean();

        $this->fail("No exception was raised for malformed input var");
    }
}
