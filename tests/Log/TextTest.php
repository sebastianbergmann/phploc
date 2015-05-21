<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Markus Schulte <email@markusschulte.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class TextTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function printResultEveryCountIsPrinted()
    {
        $output = new TestOutput();

        $count = FixtureHelper::getSampleRow();

        $textLogger = new \SebastianBergmann\PHPLOC\Log\Text();
        $textLogger->printResult($output, $count, true);
        $rawResult = $output->output;
        $rows = explode("\n", $rawResult);
        $rows = array_filter($rows);

        // At text output, there are seven summary-lines, without a values, such as "Tests".
        $summaryLinesCount = 7;
        $this->assertCount(count($count) + $summaryLinesCount, $rows);
    }
}

class TestOutput extends \Symfony\Component\Console\Output\Output
{
    public $output = '';

    public function clear()
    {
        $this->output = '';
    }

    protected function doWrite($message, $newline)
    {
        $this->output .= $message . ($newline ? "\n" : '');
    }
}
