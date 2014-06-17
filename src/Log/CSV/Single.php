<?php
/**
 * phploc
 *
 * Copyright (c) 2009-2014, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package   phploc
 * @author    Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright 2009-2014 Sebastian Bergmann <sebastian@phpunit.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @since     File available since Release 2.0.0
 */

namespace SebastianBergmann\PHPLOC\Log\CSV
{
    /**
     * A CSV ResultPrinter for the TextUI.
     *
     * @author    Sebastian Bergmann <sebastian@phpunit.de>
     * @copyright 2009-2014 Sebastian Bergmann <sebastian@phpunit.de>
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
        private $colmap = array(
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
            'methodCcnAvg' => 'Cyclomatic Complexity / Number of Methods',
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
        );
        
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
            $values = array();
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
}
