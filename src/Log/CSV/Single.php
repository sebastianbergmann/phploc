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
        'directories'                 => 'Directories',
        'files'                       => 'Files',
        'loc'                         => 'Lines of Code (LOC)',
        'ccnByLloc'                   => 'Cyclomatic Complexity / Lines of Code',
        'cloc'                        => 'Comment Lines of Code (CLOC)',
        'ncloc'                       => 'Non-Comment Lines of Code (NCLOC)',
        'lloc'                        => 'Logical Lines of Code (LLOC)',
        'llocGlobal'                  => 'LLOC outside functions or classes',
        'namespaces'                  => 'Namespaces',
        'interfaces'                  => 'Interfaces',
        'traits'                      => 'Traits',
        'classes'                     => 'Classes',
        'abstractClasses'             => 'Abstract Classes',
        'concreteClasses'             => 'Concrete Classes',
        'llocClasses'                 => 'Classes Length (LLOC)',
        'methods'                     => 'Methods',
        'nonStaticMethods'            => 'Non-Static Methods',
        'staticMethods'               => 'Static Methods',
        'publicMethods'               => 'Public Methods',
        'nonPublicMethods'            => 'Non-Public Methods',
        'methodCcnAvg'                => 'Cyclomatic Complexity / Number of Methods',
        'functions'                   => 'Functions',
        'namedFunctions'              => 'Named Functions',
        'anonymousFunctions'          => 'Anonymous Functions',
        'llocFunctions'               => 'Functions Length (LLOC)',
        'llocByNof'                   => 'Average Function Length (LLOC)',
        'constants'                   => 'Constants',
        'globalConstants'             => 'Global Constants',
        'classConstants'              => 'Class Constants',
        'attributeAccesses'           => 'Attribute Accesses',
        'instanceAttributeAccesses'   => 'Non-Static Attribute Accesses',
        'staticAttributeAccesses'     => 'Static Attribute Accesses',
        'methodCalls'                 => 'Method Calls',
        'instanceMethodCalls'         => 'Non-Static Method Calls',
        'staticMethodCalls'           => 'Static Method Calls',
        'globalAccesses'              => 'Global Accesses',
        'globalVariableAccesses'      => 'Global Variable Accesses',
        'superGlobalVariableAccesses' => 'Super-Global Variable Accesses',
        'globalConstantAccesses'      => 'Global Constant Accesses',
        'testClasses'                 => 'Test Classes',
        'testMethods'                 => 'Test Methods'
    ];

    /**
     * Prints a result set.
     *
     * @param string $filename
     * @param array  $count
     */
    public function printResult($filename, array $count)
    {
        file_put_contents(
            $filename,
            $this->getKeysLine($count) . $this->getValuesLine($count)
        );
    }

    /**
     * Adds a new result line to the file. Will create and add headers if this is the first attempt.
     *
     * @param string $filename
     * @param array $count
     */
    public function addResult($filename, array $count)
    {
        static $createdFirstLine = false;

        if($createdFirstLine) {
            file_put_contents($filename, file_get_contents($filename).$this->getValuesLine($count));
        } else {
            $this->printResult($filename, $count);
            $createdFirstLine = true;
        }
    }

    /**
     * @param  array  $count
     * @return string
     */
    protected function getKeysLine(array $count)
    {
        if(isset($count['project_directory'])) {
            $this->colmap = array_merge(
                ['project_directory' => 'Project Directory'],
                $this->colmap
            );
        }

        return implode(',', array_values($this->colmap)) . PHP_EOL;
    }

    /**
     * @param  array                     $count
     * @throws \InvalidArgumentException
     * @return string
     */
    protected function getValuesLine(array $count)
    {
        $values = [];

        if(isset($count['project_directory']) && !isset($this->colmap['project_directory'])) {
            $this->colmap = array_merge(
                ['project_directory' => 'Project Directory'],
                $this->colmap
            );
        }

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
