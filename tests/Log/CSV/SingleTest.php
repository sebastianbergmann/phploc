<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class SingleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SebastianBergmann\PHPLOC\Log\CSV\Single
     */
    private $single;

    private $sample_row = [
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
    ];

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

    public function testProjectSeparation()
    {
        $sample1 = array_merge(['project_directory' => rand()], $this->sample_row);
        $sample2 = array_merge(['project_directory' => rand()], $this->sample_row);

        // Clean previous CSV file.
        $this->setUp();

        ob_start();
        $this->single->addResult('php://output', $sample1);
        $this->single->addResult('php://output', $sample2);
        $output = ob_get_clean();

        $this->assertRegExp('#Project Directory,Directories,Files.+$#is', $output, "Printed result does not contain project directory header");

        $rows = explode("\n", trim($output));
        $this->assertEquals(3, count($rows), "Printed result contained more or less than expected 3 rows (1 header and 2 project lines)");
    }

}
