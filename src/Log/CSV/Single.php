<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\PHPLOC\Log\CSV;

/**
 * A CSV ResultPrinter for the TextUI.
 *
 * @author    Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright Sebastian Bergmann <sebastian@phpunit.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link      http://github.com/sebastianbergmann/phploc/tree
 * @since     Class available since Release 1.6.0
 */
class Single
{
    /**
     * Mapping between internal and human-readable metric names
     *
     * @var array
     */
    private $colmap = [
        'directories' => 'Directories',
        'files' => 'Files',
        'loc' => 'Lines of Code (LOC)',
        'ccnByLloc' => 'Cyclomatic Complexity / Lines of Code',
        'cloc' => 'Comment Lines of Code (CLOC)',
        'ncloc' => 'Non-Comment Lines of Code (NCLOC)',
        'lloc' => 'Logical Lines of Code (LLOC)',
        'llocGlobal' => 'LLOC outside functions or classes',
        'namespaces' => 'Namespaces',
        'interfaces' => 'Interfaces',
        'traits' => 'Traits',
        'classes' => 'Classes',
        'abstractClasses' => 'Abstract Classes',
        'concreteClasses' => 'Concrete Classes',
        'llocClasses' => 'Classes Length (LLOC)',
        'methods' => 'Methods',
        'nonStaticMethods' => 'Non-Static Methods',
        'staticMethods' => 'Static Methods',
        'publicMethods' => 'Public Methods',
        'nonPublicMethods' => 'Non-Public Methods',
        'classLlocAvg' => 'Average Class Length (LLOC)',
        'classLlocMin' => 'Minimum Class Length (LLOC)',
        'classLlocMax' => 'Maximum Class Length (LLOC)',
        'classCcnAvg' => 'Cyclomatic Complexity / Number of Classes',
        'classCcnMin' => 'Minimum Class Complexity',
        'classCcnMax' => 'Maximum Class Complexity',
        'methodLlocAvg' => 'Average Method Length (LLOC)',
        'methodLlocMin' => 'Minimal Method Length (LLOC)',
        'methodLlocMax' => 'Maximal Method Length (LLOC)',
        'methodCcnAvg' => 'Cyclomatic Complexity / Number of Methods',
        'methodCcnMin' => 'Minimum Method Complexity',
        'methodCcnMax' => 'Maximum Method Complexity',
        'functions' => 'Functions',
        'namedFunctions' => 'Named Functions',
        'anonymousFunctions' => 'Anonymous Functions',
        'llocFunctions' => 'Functions Length (LLOC)',
        'llocByNof' => 'Average Function Length (LLOC)',
        'constants' => 'Constants',
        'globalConstants' => 'Global Constants',
        'classConstants' => 'Class Constants',
        'attributeAccesses' => 'Attribute Accesses',
        'instanceAttributeAccesses' => 'Non-Static Attribute Accesses',
        'staticAttributeAccesses' => 'Static Attribute Accesses',
        'methodCalls' => 'Method Calls',
        'instanceMethodCalls' => 'Non-Static Method Calls',
        'staticMethodCalls' => 'Static Method Calls',
        'globalAccesses' => 'Global Accesses',
        'globalVariableAccesses' => 'Global Variable Accesses',
        'superGlobalVariableAccesses' => 'Super-Global Variable Accesses',
        'globalConstantAccesses' => 'Global Constant Accesses',
        'testClasses' => 'Test Classes',
        'testMethods' => 'Test Methods'
    ];

    /**
     * Prints a result set.
     *
     * @param string $filename
     * @param array $count
     */
    public function printResult($filename, array $count)
    {
        file_put_contents(
            $filename,
            $this->getKeysLine($count) . $this->getValuesLine($count)
        );
    }

    /**
     * @param  array $count
     * @return string
     */
    protected function getKeysLine(array $count)
    {
        return implode(',', array_values($this->colmap)) . PHP_EOL;
    }

    /**
     * @param  array $count
     * @throws \InvalidArgumentException
     * @return string
     */
    protected function getValuesLine(array $count)
    {
        $values = [];

        foreach ($this->colmap as $key => $name) {
            if (isset($count[$key])) {
                $values[] = $count[$key];
            } else {
                throw new \InvalidArgumentException('Attempted to print row with missing keys');
            }
        }

        return '"' . implode('","', $values) . '"' . PHP_EOL;
    }
}
