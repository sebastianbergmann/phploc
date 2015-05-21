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

    /**
     * @var array
     */
    private static $sample_data;

    /**
     * @beforeClass
     */
    public static function setUpSampleRow()
    {
        $sampleRow1 = FixtureHelper::getSampleRow();
        $sampleRow2 = FixtureHelper::getSampleRow();
        // Have values for the two sample rows disjunct.
        $sampleRowsCount = count($sampleRow1);
        foreach (array_keys($sampleRow2) as $metricName) {
            $sampleRow2[$metricName] += $sampleRowsCount;
        }

        $data1 = ['commit' => 'foo'] + $sampleRow1;
        $data2 = ['commit' => 'bar'] + $sampleRow2;

        static::$sample_data = [
            '2014-06-09T00:00:00' => $data1,
            '2014-07-09T00:00:00' => $data2,
        ];
    }

    protected function setUp()
    {
        $this->history = new \SebastianBergmann\PHPLOC\Log\CSV\History();
    }

    public function testPrintedResultContainsHeadings()
    {
        ob_start();
        $this->history->printResult('php://output', static::$sample_data);
        $output = ob_get_clean();

        $this->assertRegExp('#^Date,Commit,Directories,Files.+$#mis', $output, "Printed result does not contain a heading line");
    }

    public function testPrintedResultContainsData()
    {
        ob_start();
        $this->history->printResult('php://output', static::$sample_data);
        $output = ob_get_clean();

        $this->assertRegExp('#^2014-06-09T00:00:00,"foo","1".+$#mis', $output, "Printed result does not contain a value line");
    }

    public function testExactlyThreeRowsArePrinted()
    {
        ob_start();
        $this->history->printResult('php://output', static::$sample_data);
        $output = ob_get_clean();

        $rows = explode("\n", trim($output));
        $this->assertEquals(3, count($rows), "Printed result contained more or less than expected 2 rows");
    }
}
