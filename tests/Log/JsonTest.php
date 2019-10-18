<?php
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

class JsonTest extends TestCase
{
    /**
     * @var \SebastianBergmann\PHPLOC\Log\Json
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
        'llocClasses'                 => 15,
        'methods'                     => 16,
        'nonStaticMethods'            => 17,
        'staticMethods'               => 18,
        'publicMethods'               => 19,
        'nonPublicMethods'            => 20,
        'methodCcnAvg'                => 21,
        'functions'                   => 22,
        'namedFunctions'              => 23,
        'anonymousFunctions'          => 24,
        'llocFunctions'               => 25,
        'llocByNof'                   => 26,
        'constants'                   => 27,
        'globalConstants'             => 28,
        'classConstants'              => 29,
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
    ];

    protected function setUp(): void
    {
        $this->single = new \SebastianBergmann\PHPLOC\Log\Json;
    }

    public function testPrintedResultIsDecodedToMatchSource(): void
    {
        \ob_start();

        $this->single->printResult('php://output', $this->sample_row);
        $output = \ob_get_clean();

        $this->assertEquals($this->sample_row, \json_decode($output, true), 'Printed result not decoded to original');
    }

    public function testPrintedResultIsDecodedToMatchSourceWithNoSubDirectories(): void
    {
        \ob_start();

        $sample_row = array_merge($this->sample_row, ['directories' => 0]);
        $this->single->printResult('php://output', $sample_row);
        $output = \ob_get_clean();

        $this->assertEquals($sample_row, \json_decode($output, true), 'Printed result not decoded to original');
    }

}
