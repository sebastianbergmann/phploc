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
 * PHPLOC code analyser.
 *
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://github.com/sebastianbergmann/phploc/tree
 * @since     Class available since Release 1.0.0
 */
class PHPLOC_Analyser
{
    protected $classes = array();

    protected $count = array(
      'files'         => 0,
      'loc'           => 0,
      'cloc'          => 0,
      'ncloc'         => 0,
      'eloc'          => 0,
      'interfaces'    => 0,
      'classes'       => 0,
      'functions'     => 0,
      'methods'       => 0,
      'staticMethods' => 0
    );

    protected $opcodeBlacklist = array('ZEND_NOP');

    /**
     * Counts LOC, ELOC, CLOC, and NCLOC as well as interfaces, classes, and
     * functions/methods for a file.
     *
     * @param string $file
     */
    public function countFile($file)
    {
        $buffer    = file_get_contents($file);
        $tokens    = token_get_all($buffer);
        $numTokens = count($tokens);
        $loc       = substr_count($buffer, "\n");
        $cloc      = 0;
        $braces    = 0;
        $class     = NULL;

        for ($i = 0; $i < $numTokens; $i++) {
            if (is_string($tokens[$i])) {
                if ($class !== NULL) {
                    if ($tokens[$i] == '{') {
                        $braces++;
                    }

                    if ($tokens[$i] == '}') {
                        $braces--;

                        if ($braces == 0) {
                            $class = NULL;
                        }
                    }
                }

                continue;
            }

            list ($token, $value) = $tokens[$i];

            if ($token == T_COMMENT || $token == T_DOC_COMMENT) {
                $cloc += substr_count($value, "\n") + 1;
            }

            else if ($token == T_CLASS || $token == T_INTERFACE) {
                $braces = 0;
                $class  = $tokens[$i+2][1];

                if ($token == T_INTERFACE) {
                    $this->count['interfaces']++;
                } else {
                    $this->count['classes']++;

                    if ($tokens[$i+4][0] == T_EXTENDS) {
                        $parent = $tokens[$i+6][1];
                    } else {
                        $parent = NULL;
                    }

                    $this->classes[$class] = $parent;
                }
            }

            else if ($token == T_FUNCTION) {
                if ($class === NULL) {
                    $this->count['functions']++;
                } else {
                    $static = FALSE;

                    for ($j = $i; $j > 0; $j--) {
                        if (is_string($tokens[$j])) {
                            if ($tokens[$j] == '{' || $tokens[$j] == '}') {
                                break;
                            }

                            continue;
                        }

                        if ($tokens[$j][0] == T_STATIC) {
                            $static = TRUE;
                        }
                    }

                    if ($static) {
                        $this->count['staticMethods']++;
                    } else {
                        $this->count['methods']++;
                    }
                }
            }
        }

        $this->count['loc']   += $loc;
        $this->count['cloc']  += $cloc;
        $this->count['ncloc'] += $loc - $cloc;
        $this->count['files']++;

        if (function_exists('bytekit_disassemble_file')) {
            $this->count['eloc'] += $this->countEloc($file);
        }
    }

    /**
     * @return array
     * @since  Method available since Release 1.1.0
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Counts the Executable Lines of Code (ELOC) using Bytekit.
     *
     * @param  string $filename
     * @return integer
     * @since  Method available since Release 1.1.0
     */
    protected function countEloc($filename)
    {
        $bytecode = bytekit_disassemble_file($filename);
        $lines    = array();

        foreach ($bytecode['functions'] as $function) {
            foreach ($function['raw']['opcodes'] as $opcode) {
                if (!isset($lines[$opcode['lineno']])) {
                    $lines[$opcode['lineno']] = TRUE;
                }
            }
        }

        return count($lines);
    }
}
?>
