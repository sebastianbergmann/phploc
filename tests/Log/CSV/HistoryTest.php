<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class HistoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SebastianBergmann\PHPLOC\Log\CSV\Single
     */
    private $history;

    private $sample_data = [
        [
            'date' => '2014-06-09T00:00:00',
            'commit' => 'foo',
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
        ],
        [
            'date' => '2014-07-09T00:00:00',
            'commit' => 'bar',
            'directories' => 42,
            'files' => 43,
            'loc' => 44,
            'ccnByLloc' => 45,
            'cloc' => 46,
            'ncloc' => 47,
            'lloc' => 48,
            'llocGlobal' => 49,
            'namespaces' => 50,
            'interfaces' => 51,
            'traits' => 52,
            'classes' => 53,
            'abstractClasses' => 54,
            'concreteClasses' => 55,
            'llocClasses' => 56,
            'methods' => 57,
            'nonStaticMethods' => 58,
            'staticMethods' => 59,
            'publicMethods' => 60,
            'nonPublicMethods' => 61,
            'methodCcnAvg' => 62,
            'functions' => 63,
            'namedFunctions' => 64,
            'anonymousFunctions' => 65,
            'llocFunctions' => 66,
            'llocByNof' => 67,
            'constants' => 68,
            'globalConstants' => 69,
            'classConstants' => 70,
            'attributeAccesses' => 71,
            'instanceAttributeAccesses' => 72,
            'staticAttributeAccesses' => 73,
            'methodCalls' => 74,
            'instanceMethodCalls' => 75,
            'staticMethodCalls' => 76,
            'globalAccesses' => 77,
            'globalVariableAccesses' => 78,
            'superGlobalVariableAccesses' => 79,
            'globalConstantAccesses' => 80,
            'testClasses' => 81,
            'testMethods' => 82
        ]
    ];

    public function setUp()
    {
        $this->history = new \SebastianBergmann\PHPLOC\Log\CSV\History('php://output');
    }

    public function testPrintedResultContainsHeadings()
    {
        ob_start();
        foreach ($this->sample_data as $row) {
            $this->history->printRow($row);
        }
        $output = ob_get_clean();

        $this->assertRegExp('#^Date,Commit,Directories,Files.+$#mis', $output, "Printed result does not contain a heading line");
    }

    public function testPrintedResultContainsData()
    {
        ob_start();
        foreach ($this->sample_data as $row) {
            $this->history->printRow($row);
        }
        $output = ob_get_clean();

        $this->assertRegExp('#^2014-06-09T00:00:00,"foo","1".+$#mis', $output, "Printed result does not contain a value line");
    }

    public function testExactlyThreeRowsArePrinted()
    {
        ob_start();
        foreach ($this->sample_data as $row) {
            $this->history->printRow($row);
        }
        $output = ob_get_clean();

        $rows = explode("\n", trim($output));
        $this->assertEquals(3, count($rows), "Printed result contained more or less than expected 2 rows");
    }
}
