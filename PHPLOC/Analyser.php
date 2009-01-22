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
 * @since     File available since Release 1.0.0
 */

/**
 *
 *
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      
 * @since     Class available since Release 1.0.0
 */
class PHPLOC_Analyser
{
    private static $opcodeBlacklist = array('ZEND_NOP');

    /**
     * Counts LOC, ELOC, CLOC, and NCLOC as well as interfaces, classes, and
     * functions/methods for a file.
     *
     * @param string $file
     * @param array  $count
     */
    public static function countFile($file, array &$count)
    {
        $buffer = file_get_contents($file);
        $loc    = substr_count($buffer, "\n");
        $cloc   = 0;

        foreach (token_get_all($buffer) as $token) {
            if (is_string($token)) {
                continue;
            }

            list ($token, $value) = $token;

            if ($token == T_COMMENT || $token == T_DOC_COMMENT) {
                $cloc += substr_count($value, "\n") + 1;
            }

            else if ($token == T_INTERFACE) {
                $count['interfaces']++;
            }

            else if ($token == T_CLASS) {
                $count['classes']++;
            }

            else if ($token == T_FUNCTION) {
                $count['functions']++;
            }
        }

        $count['loc']   += $loc;
        $count['cloc']  += $cloc;
        $count['ncloc'] += $loc - $cloc;
        $count['files']++;

        if (function_exists('parsekit_compile_file')) {
            $count['eloc'] += self::countOpArray(parsekit_compile_file($file));
        }
    }

    /**
     * Counts ELOC from a given op-array.
     *
     * @param  array $opArray
     * @return integer
     */
    protected static function countOpArray(array $opArray)
    {
        $eloc  = 0;
        $lines = array();

        if (isset($opArray['class_table'])) {
            foreach ($opArray['class_table'] as $_class) {
                if (isset($_class['function_table'])) {
                    foreach ($_class['function_table'] as $_opArray) {
                        $eloc += self::countOpArray($_opArray);
                    }
                }
            }
        }

        if (isset($opArray['function_table'])) {
            foreach ($opArray['function_table'] as $_opArray) {
                $eloc += self::countOpArray($_opArray);
            }
        }

        foreach ($opArray['opcodes'] as $opcode) {
            if (!isset($eloc[$opcode['lineno']]) &&
                !in_array($opcode['opcode_name'], self::$opcodeBlacklist)) {
                $lines[$opcode['lineno']] = TRUE;
            }
        }

        return $eloc + count($lines);
    }
}
?>
