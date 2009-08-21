<?php
/**
 * phploc
 *
 * Copyright (c) 2009, Sebastian Bergmann <sb@sebastian-bergmann.de>.
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
 * @copyright 2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @since     File available since Release 1.1.0
 */

/**
 * A ResultPrinter for the TextUI.
 *
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://github.com/sebastianbergmann/phploc/tree
 * @since     Class available since Release 1.0.0
 */
class PHPLOC_TextUI_ResultPrinter_Text
{
    /**
     * Prints a result set.
     *
     * @param array $count
     */
    public function printResult(array $count)
    {
        $args   = array();
        $format = '';

        if ($count['directories'] > 0) {
            $args[]  = $count['directories'];
            $args[]  = $count['files'];

            $format .= "Directories:                       %10d\n" .
                       "Files:                             %10d\n\n";
        }

        $args[]  = $count['loc'];
        $format .= "Lines of Code (LOC):               %10d\n";

        if (isset($count['eloc'])) {
            $args[]  = $count['eloc'];
            $format .= "Executable Lines of Code (ELOC):   %10d\n";
        }

        $args[] = $count['cloc'];
        $args[] = $count['ncloc'];
        $args[] = $count['interfaces'];
        $args[] = $count['abstractClasses'];
        $args[] = $count['classes'];
        $args[] = $count['methods'];
        $args[] = $count['staticMethods'];
        $args[] = $count['functions'];
        $args[] = $count['constants'];
        $args[] = $count['classConstants'];

        $format .= "Comment Lines of Code (CLOC):      %10d\n" .
                   "Non-Comment Lines of Code (NCLOC): %10d\n\n" .
                   "Interfaces:                        %10d\n" .
                   "Abstract Classes:                  %10d\n" .
                   "Classes:                           %10d\n" .
                   "Non-Static Methods:                %10d\n" .
                   "Static Methods:                    %10d\n" .
                   "Functions:                         %10d\n" .
                   "Constants:                         %10d\n" .
                   "Class constants:                   %10d\n";

        vprintf($format, $args);
    }
}
?>
