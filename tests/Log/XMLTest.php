<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Markus Schulte <email@markusschulte.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class XMLTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function printResultEveryCountIsPrinted()
    {
        $sample_row = FixtureHelper::getSampleRow();
        $text_logger = new \SebastianBergmann\PHPLOC\Log\XML();

        ob_start();
        $text_logger->printResult('php://output', $sample_row);
        $raw_output = ob_get_clean();

        $xml = new SimpleXMLElement($raw_output);
        $this->assertEquals(count($sample_row), $xml->count());
        foreach ($sample_row as $metric_name => $metric_value) {
            $xml_row = $xml->$metric_name;
            $this->assertNotNull($xml_row);

            $xml_row_value = (String)$xml_row;
            $this->assertEquals($metric_value, $xml_row_value);
        }

        /* @var $xml_row SimpleXMLElement */
        foreach($xml as $xml_row) {
            $xml_row_name = $xml_row->getName();
            $this->assertArrayHasKey($xml_row_name, $sample_row);
        }
    }
}
