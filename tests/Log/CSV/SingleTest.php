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
     * @var array
     */
    private static $sample_row;

    /**
     * @var \SebastianBergmann\PHPLOC\Log\CSV\Single
     */
    private $single;

    /**
     * @beforeClass
     */
    public static function setUpSampleRow()
    {
        static::$sample_row = FixtureHelper::getSampleRow();
    }

    protected function setUp()
    {
        $this->single = new \SebastianBergmann\PHPLOC\Log\CSV\Single();
    }

    public function testPrintedResultContainsHeadings()
    {
        ob_start();
        $this->single->printResult('php://output', static::$sample_row);
        $output = ob_get_clean();

        $this->assertRegExp('#Directories,Files.+$#is', $output, "Printed result does not contain a heading line");
    }

    public function testPrintedResultContainsData()
    {
        ob_start();
        $this->single->printResult('php://output', static::$sample_row);
        $rawOutput = ob_get_clean();

        $outputLines = explode("\n", $rawOutput);
        $outputLines = array_filter($outputLines);
        $this->assertCount(
            2,
            $outputLines,
            'Result should contain one heading- and one data-line'
        );

        $data = explode(',', end($outputLines));

        $this->assertRegExp('#"1","2".+$#is', $rawOutput, 'Printed result does not contain a value line');
        $this->assertCount(
            count(static::$sample_row),
            $data,
            'Result is missing some metrics'
        );
    }

    public function testPrintedResultContainsEqualNumHeadingsAndValues()
    {
        ob_start();
        $this->single->printResult('php://output', static::$sample_row);
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
        $this->single->printResult('php://output', static::$sample_row);
        $output = ob_get_clean();

        $rows = explode("\n", trim($output));
        $this->assertEquals(2, count($rows), "Printed result contained more or less than expected 2 rows");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPrintPartialRow()
    {
        $count = static::$sample_row;
        unset($count['llocByNof']);

        ob_start();
        $this->single->printResult('php://output', $count);
        ob_end_clean();

        $this->fail("No exception was raised for malformed input var");
    }
}