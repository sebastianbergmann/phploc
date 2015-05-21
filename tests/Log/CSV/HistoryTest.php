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
     * @var array
     */
    private static $sample_data;

    /**
     * @var \SebastianBergmann\PHPLOC\Log\CSV\History
     */
    private $history;

    /**
     * @beforeClass
     */
    public static function setUpSampleRow()
    {
        $sample_row_1 = FixtureHelper::getSampleRow();
        $sample_row_2 = FixtureHelper::getSampleRow();
        // Have values for the two sample rows disjunct.
        $sample_rows_count = count($sample_row_1);
        foreach (array_keys($sample_row_2) as $metric_name) {
            $sample_row_2[$metric_name] += $sample_rows_count;
        }

        $data1 = ['commit' => 'foo'] + $sample_row_1;
        $data2 = ['commit' => 'bar'] + $sample_row_2;

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

        $rows = explode(PHP_EOL, trim($output));
        $this->assertEquals(3, count($rows), "Printed result contained more or less than expected 2 rows");
    }
}
