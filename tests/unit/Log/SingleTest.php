<?php declare(strict_types=1);
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\PHPLOC;

use PHPUnit\Framework\TestCase;

class SingleTest extends TestCase
{
    /**
     * @var \SebastianBergmann\PHPLOC\Log\Csv
     */
    private $single;

    private $sample_row = [
        'directories'                 => 1,
        'files'                       => 2,
        'loc'                         => 3,
        'ccnByLloc'                   => 4,
        'cloc'                        => 5,
        'ncloc'                       => 6,
        'lloc'                        => 7,
        'llocGlobal'                  => 8,
        'namespaces'                  => 9,
        'interfaces'                  => 10,
        'traits'                      => 11,
        'classes'                     => 12,
        'abstractClasses'             => 13,
        'concreteClasses'             => 14,
        'finalClasses'                => 8,
        'nonFinalClasses'             => 6,
        'llocClasses'                 => 15,
        'methods'                     => 16,
        'nonStaticMethods'            => 17,
        'staticMethods'               => 18,
        'publicMethods'               => 19,
        'nonPublicMethods'            => 20,
        'protectedMethods'            => 14,
        'privateMethods'              => 6,
        'methodCcnAvg'                => 21,
        'functions'                   => 22,
        'namedFunctions'              => 23,
        'anonymousFunctions'          => 24,
        'llocFunctions'               => 25,
        'llocByNof'                   => 26,
        'constants'                   => 27,
        'globalConstants'             => 28,
        'classConstants'              => 29,
        'publicClassConstants'        => 15,
        'nonPublicClassConstants'     => 14,
        'attributeAccesses'           => 30,
        'instanceAttributeAccesses'   => 31,
        'staticAttributeAccesses'     => 32,
        'methodCalls'                 => 33,
        'instanceMethodCalls'         => 34,
        'staticMethodCalls'           => 35,
        'globalAccesses'              => 36,
        'globalVariableAccesses'      => 37,
        'superGlobalVariableAccesses' => 38,
        'globalConstantAccesses'      => 39,
        'testClasses'                 => 40,
        'testMethods'                 => 41,
        'classCcnAvg'                 => 42,
        'classLlocAvg'                => 43,
        'methodLlocAvg'               => 44,
        'averageMethodsPerClass'      => 5
    ];

    protected function setUp(): void
    {
        $this->single = new \SebastianBergmann\PHPLOC\Log\Csv;
    }

    public function testPrintedResultContainsHeadings(): void
    {
        \ob_start();

        $this->single->printResult('php://output', $this->sample_row);
        $output = \ob_get_clean();

        $this->assertRegExp('#Directories,Files.+$#is', $output, 'Printed result does not contain a heading line');
    }

    public function testPrintedResultContainsData(): void
    {
        \ob_start();

        $this->single->printResult('php://output', $this->sample_row);
        $output = \ob_get_clean();

        $this->assertRegExp('#"1","2".+$#is', $output, 'Printed result does not contain a value line');
    }

    public function testPrintedResultContainsEqualNumHeadingsAndValues(): void
    {
        \ob_start();

        $this->single->printResult('php://output', $this->sample_row);
        $output = \ob_get_clean();

        $rows     = \explode("\n", $output);
        $headings = \explode(',', $rows[0]);
        $vals     = \explode(',', $rows[1]);

        $this->assertEquals(
            \count($headings),
            \count($vals),
            'Printed result does not contain same number of headings and values'
        );
    }

    public function testExactlyTwoRowsArePrinted(): void
    {
        \ob_start();

        $this->single->printResult('php://output', $this->sample_row);
        $output = \ob_get_clean();

        $rows = \explode("\n", \trim($output));
        $this->assertEquals(2, \count($rows), 'Printed result contained more or less than expected 2 rows');
    }

    public function testPrintPartialRow(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $count = $this->sample_row;
        unset($count['llocByNof']);

        try {
            \ob_start();
            $this->single->printResult('php://output', $count);
        } finally {
            \ob_end_clean();
        }

        $this->fail('No exception was raised for malformed input var');
    }
}
