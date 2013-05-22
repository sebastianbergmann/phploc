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
 * @since     File available since Release 1.6.0
 */

namespace SebastianBergmann\PHPLOC\Log
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
    class CSV
    {
        /**
         * Prints a result set.
         *
         * @param string $filename
         * @param array  $count
         */
        public function printResult($filename, array $count)
        {
            $buffer = $this->getKeysLine($count);

            if (isset($count['loc'])) {
                $count = array($count);
            }

            foreach ($count as $_count) {
                $values = array();

                if ($_count['directories'] > 0) {
                    $values[] = $_count['directories'];
                    $values[] = $_count['files'];
                }

                $values[] = $_count['loc'];
                $values[] = $_count['ccnByLoc'];

                if (isset($_count['eloc'])) {
                    $values[] = $_count['eloc'];
                }

                $values[] = $_count['cloc'];
                $values[] = $_count['ncloc'];
                $values[] = $_count['namespaces'];
                $values[] = $_count['interfaces'];
                $values[] = $_count['traits'];
                $values[] = $_count['classes'];
                $values[] = $_count['abstractClasses'];
                $values[] = $_count['concreteClasses'];
                $values[] = $_count['nclocByNoc'];
                $values[] = $_count['methods'];
                $values[] = $_count['nonStaticMethods'];
                $values[] = $_count['staticMethods'];
                $values[] = $_count['publicMethods'];
                $values[] = $_count['nonPublicMethods'];
                $values[] = $_count['nclocByNom'];
                $values[] = $_count['ccnByNom'];
                $values[] = $_count['anonymousFunctions'];
                $values[] = $_count['functions'];
                $values[] = $_count['constants'];
                $values[] = $_count['globalConstants'];
                $values[] = $_count['classConstants'];

                if (isset($_count['testClasses'])) {
                    $values[] = $_count['testClasses'];
                    $values[] = $_count['testMethods'];
                }

                $buffer .= implode(',', $values) . PHP_EOL;
            }

            file_put_contents($filename, $buffer);
        }

        /**
         * @param  array $count
         * @return string
         */
        private function getKeysLine(array $count)
        {
            $keys = array();
 
            if (!isset($count['loc'])) {
                $_keys = array_keys($count);
                $count = $count[$_keys[0]];
            }

            if ($count['directories'] > 0) {
                $keys[]   = 'Directories';
                $keys[]   = 'Files';
            }

            $keys[]   = 'Lines of Code (LOC)';
            $keys[]   = 'Cyclomatic Complexity / Lines of Code';

            if (isset($count['eloc'])) {
                $keys[]   = 'Executable Lines of Code (ELOC)';
            }

            $keys[]   = 'Comment Lines of Code (CLOC)';
            $keys[]   = 'Non-Comment Lines of Code (NCLOC)';
            $keys[]   = 'Namespaces';
            $keys[]   = 'Interfaces';
            $keys[]   = 'Traits';
            $keys[]   = 'Classes';
            $keys[]   = 'Abstract Classes';
            $keys[]   = 'Concrete Classes';
            $keys[]   = 'Average Class Length (NCLOC)';
            $keys[]   = 'Methods';
            $keys[]   = 'Non-Static Methods';
            $keys[]   = 'Static Methods';
            $keys[]   = 'Public Methods';
            $keys[]   = 'Non-Public Methods';
            $keys[]   = 'Average Method Length (NCLOC)';
            $keys[]   = 'Cyclomatic Complexity / Number of Methods';
            $keys[]   = 'Anonymous Functions';
            $keys[]   = 'Functions';
            $keys[]   = 'Constants';
            $keys[]   = 'Global Constants';
            $keys[]   = 'Class Constants';

            if (isset($count['testClasses'])) {
                $keys[]   = 'Test Classes';
                $keys[]   = 'Test Methods';
            }

            return implode(',', $keys) . PHP_EOL;
        }
    }
}
