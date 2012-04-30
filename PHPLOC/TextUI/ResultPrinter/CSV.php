<?php
/**
 * phploc
 *
 * Copyright (c) 2009-2012, Sebastian Bergmann <sb@sebastian-bergmann.de>.
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
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @since     File available since Release 1.6.0
 */

/**
 * A CSV ResultPrinter for the TextUI.
 *
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version   Release: @package_version@
 * @link      http://github.com/sebastianbergmann/phploc/tree
 * @since     Class available since Release 1.6.0
 */
class PHPLOC_TextUI_ResultPrinter_CSV
{
    /**
     * Prints a result set.
     *
     * @param string $filename
     * @param array  $count
     */
    public function printResult($filename, array $count)
    {
        $keys   = array();
        $values = array();

        if ($count['directories'] > 0) {
            $keys[]   = 'Directories';
            $values[] = $count['directories'];
            $keys[]   = 'Files';
            $values[] = $count['files'];
        }

        $keys[]   = 'Lines of Code (LOC)';
        $values[] = $count['loc'];
        $keys[]   = 'Cyclomatic Complexity / Lines of Code';
        $values[] = $count['ccnByLoc'];

        if (isset($count['eloc'])) {
            $keys[]   = 'Executable Lines of Code (ELOC)';
            $values[] = $count['eloc'];
        }

        $keys[]   = 'Comment Lines of Code (CLOC)';
        $values[] = $count['cloc'];
        $keys[]   = 'Non-Comment Lines of Code (NCLOC)';
        $values[] = $count['ncloc'];
        $keys[]   = 'Namespaces';
        $values[] = $count['namespaces'];
        $keys[]   = 'Interfaces';
        $values[] = $count['interfaces'];
        $keys[]   = 'Traits';
        $values[] = $count['traits'];
        $keys[]   = 'Classes';
        $values[] = $count['classes'];
        $keys[]   = 'Abstract Classes';
        $values[] = $count['abstractClasses'];
        $keys[]   = 'Concrete Classes';
        $values[] = $count['concreteClasses'];
        $keys[]   = 'Average Class Length (NCLOC)';
        $values[] = $count['nclocByNoc'];
        $keys[]   = 'Methods';
        $values[] = $count['methods'];
        $keys[]   = 'Non-Static Methods';
        $values[] = $count['nonStaticMethods'];
        $keys[]   = 'Static Methods';
        $values[] = $count['staticMethods'];
        $keys[]   = 'Public Methods';
        $values[] = $count['publicMethods'];
        $keys[]   = 'Non-Public Methods';
        $values[] = $count['nonPublicMethods'];
        $keys[]   = 'Average Method Length (NCLOC)';
        $values[] = $count['nclocByNom'];
        $keys[]   = 'Cyclomatic Complexity / Number of Methods';
        $values[] = $count['ccnByNom'];
        $keys[]   = 'Anonymous Functions';
        $values[] = $count['anonymousFunctions'];
        $keys[]   = 'Functions';
        $values[] = $count['functions'];
        $keys[]   = 'Constants';
        $values[] = $count['constants'];
        $keys[]   = 'Global Constants';
        $values[] = $count['globalConstants'];
        $keys[]   = 'Class Constants';
        $values[] = $count['classConstants'];

        if (isset($count['testClasses'])) {
            $keys[]   = 'Test Clases';
            $values[] = $count['testClasses'];
            $keys[]   = 'Test Methods';
            $values[] = $count['testMethods'];
        }

        file_put_contents(
          $filename, implode(',', $keys) . PHP_EOL . implode(',', $values)
        );
    }
}
