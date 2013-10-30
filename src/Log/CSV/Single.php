<?php
/**
 * phploc
 *
 * Copyright (c) 2009-2013, Sebastian Bergmann <sebastian@phpunit.de>.
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
 * @copyright 2009-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @since     File available since Release 2.0.0
 */

namespace SebastianBergmann\PHPLOC\Log\CSV
{
    /**
     * A CSV ResultPrinter for the TextUI.
     *
     * @author    Sebastian Bergmann <sebastian@phpunit.de>
     * @copyright 2009-2013 Sebastian Bergmann <sebastian@phpunit.de>
     * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
     * @link      http://github.com/sebastianbergmann/phploc/tree
     * @since     Class available since Release 1.6.0
     */
    class Single
    {
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
            $keys = array(
              'Directories',
              'Files',
              'Lines of Code (LOC)',
              'Cyclomatic Complexity / Lines of Code',
              'Comment Lines of Code (CLOC)',
              'Non-Comment Lines of Code (NCLOC)',
              'Logical Lines of Code (LLOC)',
              'LLOC outside functions or classes',
              'Namespaces',
              'Interfaces',
              'Traits',
              'Classes',
              'Abstract Classes',
              'Concrete Classes',
              'Classes Length (LLOC)',
              'Average Class Length (LLOC)',
              'Methods',
              'Non-Static Methods',
              'Static Methods',
              'Public Methods',
              'Non-Public Methods',
              'Average Method Length (LLOC)',
              'Cyclomatic Complexity / Number of Methods',
              'Functions',
              'Named Functions',
              'Anonymous Functions',
              'Functions Length (LLOC)',
              'Average Function Length (LLOC)',
              'Constants',
              'Global Constants',
              'Class Constants',
              'Attribute Accesses',
              'Non-Static Attribute Accesses',
              'Static Attribute Accesses',
              'Method Calls',
              'Non-Static Method Calls',
              'Static Method Calls',
              'Global Accesses',
              'Global Variable Accesses',
              'Super-Global Variable Accesses',
              'Global Constant Accesses',
              'Test Classes',
              'Test Methods'
            );

            return implode(',', $keys) . PHP_EOL;
        }

        /**
         * @param  array $count
         * @return string
         */
        protected function getValuesLine(array $count)
        {
            $values = array(
              $count['directories'],
              $count['files'],
              $count['loc'],
              $count['ccnByLloc'],
              $count['cloc'],
              $count['ncloc'],
              $count['lloc'],
              $count['llocGlobal'],
              $count['namespaces'],
              $count['interfaces'],
              $count['traits'],
              $count['classes'],
              $count['abstractClasses'],
              $count['concreteClasses'],
              $count['llocClasses'],
              $count['llocByNoc'],
              $count['methods'],
              $count['nonStaticMethods'],
              $count['staticMethods'],
              $count['publicMethods'],
              $count['nonPublicMethods'],
              $count['llocByNom'],
              $count['ccnByNom'],
              $count['functions'],
              $count['namedFunctions'],
              $count['anonymousFunctions'],
              $count['llocFunctions'],
              $count['llocByNof'],
              $count['constants'],
              $count['globalConstants'],
              $count['classConstants'],
              $count['attributeAccesses'],
              $count['instanceAttributeAccesses'],
              $count['staticAttributeAccesses'],
              $count['methodCalls'],
              $count['instanceMethodCalls'],
              $count['staticMethodCalls'],
              $count['globalAccesses'],
              $count['globalVariableAccesses'],
              $count['superGlobalVariableAccesses'],
              $count['globalConstantAccesses'],
              $count['testClasses'],
              $count['testMethods']
            );

            return '"' . implode('","', $values) . '"' . PHP_EOL;
        }
    }
}
