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
      'files'            => 0,
      'loc'              => 0,
      'cloc'             => 0,
      'ncloc'            => 0,
      'eloc'             => 0,
      'ccn'              => 0,
      'ccnMethods'       => 0,
      'interfaces'       => 0,
      'classes'          => 0,
      'abstractClasses'  => 0,
      'concreteClasses'  => 0,
      'functions'        => 0,
      'methods'          => 0,
      'nonStaticMethods' => 0,
      'staticMethods'    => 0,
      'constants'        => 0,
      'classConstants'   => 0,
      'testClasses'      => 0,
      'testMethods'      => 0,
      'ccnByLoc'         => 0,
      'ccnByNom'         => 0,
      'locByNoc'         => 0,
      'locByNom'         => 0,
    );

    protected $opcodeBlacklist = array(
      'BYTEKIT_NOP' => TRUE
    );

    /**
     * Processes a set of files.
     *
     * @param  array   $files
     * @param  boolean $countTests
     * @return array
     * @since  Method available since Release 1.2.0
     */
    public function countFiles(array $files, $countTests)
    {
        if ($countTests) {
            foreach ($files as $file) {
                $this->preProcessFile($file->getPathName());
            }
        }

        $directories = array();

        foreach ($files as $file) {
            $directory = $file->getPath();

            if (!isset($directories[$directory])) {
                $directories[$directory] = TRUE;
            }

            $this->countFile($file->getPathName(), $countTests);
        }

        $count = $this->count;

        if (!function_exists('bytekit_disassemble_file')) {
            unset($count['eloc']);
        }

        if ($count['testClasses'] == 0) {
            unset($count['testClasses'], $count['testMethods']);
        }

        $count['directories'] = count($directories) - 1;
        $count['classes']     = $count['abstractClasses'] +
                                $count['concreteClasses'];
        $count['methods']     = $count['staticMethods'] +
                                $count['nonStaticMethods'];

        if ($count['eloc'] > 0) {
            $count['ccnByLoc'] = $count['ccn'] / $count['eloc'];
        }

        else if ($count['ncloc'] > 0) {
            $count['ccnByLoc'] = $count['ccn'] / $count['ncloc'];
        }

        if ($count['methods'] > 0) {
            $count['ccnByNom'] = $count['ccnMethods'] / $count['methods'];
        }

        if ($count['classes'] > 0) {
            $count['locByNoc'] = $count['loc'] / $count['classes'];
        }

        if ($count['methods'] > 0) {
            $count['locByNom'] = $count['loc'] / $count['methods'];
        }

        return $count;
    }

    /**
     * Pre-processes a single file.
     *
     * @param string $file
     * @since  Method available since Release 1.2.0
     */
    public function preProcessFile($file)
    {
        $tokens    = token_get_all(file_get_contents($file));
        $numTokens = count($tokens);

        for ($i = 0; $i < $numTokens; $i++) {
            if (is_string($tokens[$i])) {
                continue;
            }

            list ($token, $value) = $tokens[$i];

            if ($token == T_CLASS) {
                $className = $tokens[$i+2][1];

                if (isset($tokens[$i+4]) && is_array($tokens[$i+4]) &&
                    $tokens[$i+4][0] == T_EXTENDS) {
                    $parent = $tokens[$i+6][1];
                } else {
                    $parent = NULL;
                }

                $this->classes[$className] = $parent;
            }
        }
    }

    /**
     * Processes a single file.
     *
     * @param string  $file
     * @param boolean $countTests
     */
    public function countFile($file, $countTests)
    {
        $buffer    = file_get_contents($file);
        $tokens    = token_get_all($buffer);
        $numTokens = count($tokens);
        $loc       = substr_count($buffer, "\n");

        unset($buffer);

        $cloc      = 0;
        $braces    = 0;
        $className = NULL;
        $testClass = FALSE;

        for ($i = 0; $i < $numTokens; $i++) {
            if (is_string($tokens[$i])) {
                if (trim($token) == '?') {
                    if ($className !== NULL) {
                        $this->count['ccnMethods']++;
                    }

                    $this->count['ccn']++;
                }

                if ($className !== NULL) {
                    if ($tokens[$i] == '{') {
                        $braces++;
                    }

                    if ($tokens[$i] == '}') {
                        $braces--;

                        if ($braces == 0) {
                            $className = NULL;
                            $testClass = FALSE;
                        }
                    }
                }

                continue;
            }

            list ($token, $value) = $tokens[$i];

            switch ($token) {
                case T_CURLY_OPEN: {
                    $braces++;
                }
                break;

                case T_IF:
                case T_ELSEIF:
                case T_FOR:
                case T_FOREACH:
                case T_WHILE:
                case T_CASE:
                case T_CATCH:
                case T_BOOLEAN_AND:
                case T_LOGICAL_AND:
                case T_BOOLEAN_OR:
                case T_LOGICAL_OR: {
                    if ($className !== NULL) {
                        $this->count['ccnMethods']++;
                    }

                    $this->count['ccn']++;
                }
                break;
            }

            if ($token == T_COMMENT || $token == T_DOC_COMMENT) {
                $cloc += substr_count($value, "\n") + 1;
            }

            else if ($token == T_STRING && $value == 'define') {
                $this->count['constants']++;
            }

            else if ($token == T_CONST) {
                $this->count['classConstants']++;
            }

            else if ($token == T_CLASS || $token == T_INTERFACE) {
                $braces    = 0;
                $className = $tokens[$i+2][1];

                if ($token == T_INTERFACE) {
                    $this->count['interfaces']++;
                } else {
                    if ($countTests && $this->isTestClass($className)) {
                        $testClass = TRUE;
                        $this->count['testClasses']++;
                    } else {
                        if (isset($tokens[$i-2]) && is_array($tokens[$i-2]) &&
                            $tokens[$i-2][0] == T_ABSTRACT) {
                            $this->count['abstractClasses']++;
                        } else {
                            $this->count['concreteClasses']++;
                        }
                    }
                }
            }

            else if ($token == T_FUNCTION) {
                if ($className === NULL) {
                    $this->count['functions']++;
                } else {
                    if (is_array($tokens[$i+2])) {
                        $methodName = $tokens[$i+2][1];
                    } else {
                        $methodName = $tokens[$i+3][1];
                    }

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
                        if ($testClass && strpos($methodName, 'test') === 0) {
                            $this->count['testMethods']++;
                        } else {
                            $this->count['nonStaticMethods']++;
                        }
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
        $bytecode = @bytekit_disassemble_file($filename);

        if (!is_array($bytecode)) {
            return 0;
        }

        $lines = array();

        foreach ($bytecode['functions'] as $function) {
            foreach ($function['raw']['opcodes'] as $opline) {
                if (!isset($this->opcodeBlacklist[$opline['opcode']]) &&
                    !isset($lines[$opline['lineno']])) {
                    $lines[$opline['lineno']] = TRUE;
                }
            }
        }

        return count($lines);
    }

    /**
     * @param  string $className
     * @return boolean
     * @since  Method available since Release 1.2.0
     */
    protected function isTestClass($className)
    {
        $parent = $this->classes[$className];
        $result = FALSE;

        // Check ancestry for PHPUnit_Framework_TestCase.
        while ($parent !== NULL) {
            if ($parent == 'PHPUnit_Framework_TestCase') {
                $result = TRUE;
                break;
            }

            if (isset($this->classes[$parent])) {
                $parent = $this->classes[$parent];
            }

            // Class has a parent that is declared in a file
            // that was not pre-processed.
            else {
                break;
            }
        }

        // Fallback: Treat the class as a test case class if the name
        // of the parent class ends with "TestCase".
        if (!$result) {
            if (substr($this->classes[$className], -8) == 'TestCase') {
                $result = TRUE;
            }
        }

        return $result;
    }
}
?>
