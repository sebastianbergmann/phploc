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
 * @since     File available since Release 1.0.0
 */

namespace SebastianBergmann\PHPLOC
{
    if (!defined('T_TRAIT')) {
        define('T_TRAIT', 1000);
    }

    /**
     * PHPLOC code analyser.
     *
     * @author    Sebastian Bergmann <sebastian@phpunit.de>
     * @copyright 2009-2013 Sebastian Bergmann <sebastian@phpunit.de>
     * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
     * @link      http://github.com/sebastianbergmann/phploc/tree
     * @since     Class available since Release 1.0.0
     */
    class Analyser
    {
        /**
         * @var array
         */
        protected $namespaces = array();

        /**
         * @var array
         */
        protected $classes = array();

        /**
         * @var array
         */
        protected $count = array(
          'files'              => 0,
          'loc'                => 0,
          'nclocClasses'       => 0,
          'cloc'               => 0,
          'ncloc'              => 0,
          'eloc'               => 0,
          'ccn'                => 0,
          'ccnMethods'         => 0,
          'interfaces'         => 0,
          'traits'             => 0,
          'classes'            => 0,
          'abstractClasses'    => 0,
          'concreteClasses'    => 0,
          'anonymousFunctions' => 0,
          'functions'          => 0,
          'methods'            => 0,
          'publicMethods'      => 0,
          'nonPublicMethods'   => 0,
          'nonStaticMethods'   => 0,
          'staticMethods'      => 0,
          'constants'          => 0,
          'classConstants'     => 0,
          'globalConstants'    => 0,
          'testClasses'        => 0,
          'testMethods'        => 0,
          'ccnByLoc'           => 0,
          'ccnByNom'           => 0,
          'nclocByNoc'         => 0,
          'nclocByNom'         => 0
        );

        /**
         * @var array
         */
        protected $opcodeBlacklist = array(
          'BYTEKIT_NOP' => TRUE
        );

        /**
         * @var ezcConsoleOutput
         */
        protected $output;

        /**
         * Constructor.
         *
         * @param ezcConsoleOutput $output
         * @since Method available since Release 1.5.0
         */
        public function __construct(\ezcConsoleOutput $output = NULL)
        {
            $this->output = $output;
        }

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
                if ($this->output !== NULL) {
                    $bar = new \ezcConsoleProgressbar($this->output, count($files));
                    print "Preprocessing files\n";
                }

                foreach ($files as $file) {
                    $this->preProcessFile($file);

                    if ($this->output !== NULL) {
                        $bar->advance();
                    }
                }

                if ($this->output !== NULL) {
                    print "\n\n";
                }
            }

            $directories = array();

            if ($this->output !== NULL) {
                $bar = new \ezcConsoleProgressbar($this->output, count($files));
                print "Processing files\n";
            }

            foreach ($files as $file) {
                $directory = dirname($file);

                if (!isset($directories[$directory])) {
                    $directories[$directory] = TRUE;
                }

                $this->countFile($file, $countTests);

                if ($this->output !== NULL) {
                    $bar->advance();
                }
            }

            if ($this->output !== NULL) {
                print "\n\n";
            }

            $count = $this->count;

            if (!function_exists('bytekit_disassemble_file')) {
                unset($count['eloc']);
            }

            if (!$countTests) {
                unset($count['testClasses']);
                unset($count['testMethods']);
            }

            $count['directories']   = count($directories) - 1;
            $count['namespaces']    = count($this->namespaces);
            $count['classes']       = $count['abstractClasses'] +
                                      $count['concreteClasses'];
            $count['methods']       = $count['staticMethods'] +
                                      $count['nonStaticMethods'];
            $count['publicMethods'] = $count['methods'] -
                                      $count['nonPublicMethods'];
            $count['constants']     = $count['classConstants'] +
                                      $count['globalConstants'];

            if (isset($count['eloc']) && $count['eloc'] > 0) {
                $count['ccnByLoc'] = $count['ccn'] / $count['eloc'];
            }

            else if ($count['ncloc'] > 0) {
                $count['ccnByLoc'] = $count['ccn'] / $count['ncloc'];
            }

            if ($count['methods'] > 0) {
                if(isset($count['testMethods'])) {
                    $countTestMethods = $count['testMethods'];
                } else {
                    $countTestMethods = 0;
                }
                $count['ccnByNom'] = 1 + ($count['ccnMethods'] /
                                     ($count['methods'] - $countTestMethods));
            }

            if ($count['classes'] > 0) {
                $count['nclocByNoc'] = $count['nclocClasses'] / $count['classes'];
            }

            if ($count['methods'] > 0) {
                $count['nclocByNom'] = $count['nclocClasses'] / $count['methods'];
            }

            return $count;
        }

        /**
         * Pre-processes a single file.
         *
         * @param string $filename
         * @since Method available since Release 1.2.0
         */
        public function preProcessFile($filename)
        {
            $tokens    = token_get_all(file_get_contents($filename));
            $numTokens = count($tokens);
            $namespace = FALSE;

            for ($i = 0; $i < $numTokens; $i++) {
                if (is_string($tokens[$i])) {
                    continue;
                }

                list ($token, $value) = $tokens[$i];

                switch ($token) {
                    case T_NAMESPACE: {
                        $namespace = $this->getNamespaceName($tokens, $i);
                    }
                    break;

                    case T_CLASS: {
                        $className = $this->getClassName($namespace, $tokens, $i);

                        if (isset($tokens[$i+4]) && is_array($tokens[$i+4]) &&
                            $tokens[$i+4][0] == T_EXTENDS) {
                            $parent = $this->getClassName(
                              $namespace, $tokens, $i + 4
                            );
                        } else {
                            $parent = NULL;
                        }

                        $this->classes[$className] = $parent;
                    }
                    break;
                }
            }
        }

        /**
         * Processes a single file.
         *
         * @param string  $filename
         * @param boolean $countTests
         */
        public function countFile($filename, $countTests)
        {
            $buffer    = file_get_contents($filename);
            $tokens    = token_get_all($buffer);
            $numTokens = count($tokens);
            $loc       = substr_count($buffer, "\n");

            unset($buffer);

            $nclocClasses = 0;
            $cloc         = 0;
            $blocks       = array();
            $currentBlock = FALSE;
            $namespace    = FALSE;
            $className    = NULL;
            $functionName = NULL;
            $testClass    = FALSE;

            for ($i = 0; $i < $numTokens; $i++) {
                if (is_string($tokens[$i])) {
                    if (trim($tokens[$i]) == '?') {
                        if (!$testClass) {
                            if ($className !== NULL) {
                                $this->count['ccnMethods']++;
                            }

                            $this->count['ccn']++;
                        }
                    }

                    if ($tokens[$i] == '{') {
                        if ($currentBlock == T_CLASS) {
                            $block = $className;
                        }

                        else if ($currentBlock == T_FUNCTION) {
                            $block = $functionName;
                        }

                        else {
                            $block = FALSE;
                        }

                        array_push($blocks, $block);

                        $currentBlock = FALSE;
                    }

                    else if ($tokens[$i] == '}') {
                        $block = array_pop($blocks);

                        if ($block !== FALSE && $block !== NULL) {
                            if ($block == $functionName) {
                                $functionName = NULL;
                            }

                            else if ($block == $className) {
                                $className = NULL;
                                $testClass = FALSE;
                            }
                        }
                    }

                    continue;
                }

                list ($token, $value) = $tokens[$i];

                if ($className !== NULL) {
                    if ($token != T_COMMENT && $token != T_DOC_COMMENT) {
                        $nclocClasses += substr_count($value, "\n");
                    }
                }

                switch ($token) {
                    case T_NAMESPACE: {
                        $namespace = $this->getNamespaceName($tokens, $i);

                        if (!isset($this->namespaces[$namespace])) {
                            $this->namespaces[$namespace] = TRUE;
                        }
                    }
                    break;

                    case T_CLASS:
                    case T_INTERFACE:
                    case T_TRAIT: {
                        $className    = $this->getClassName(
                                          $namespace, $tokens, $i
                                        );
                        $currentBlock = T_CLASS;

                        if ($token == T_TRAIT) {
                            $this->count['traits']++;
                        }

                        else if ($token == T_INTERFACE) {
                            $this->count['interfaces']++;
                        }

                        else {
                            if ($countTests && $this->isTestClass($className)) {
                                $testClass = TRUE;
                                $this->count['testClasses']++;
                            } else {
                                if (isset($tokens[$i-2]) &&
                                    is_array($tokens[$i-2]) &&
                                    $tokens[$i-2][0] == T_ABSTRACT) {
                                    $this->count['abstractClasses']++;
                                } else {
                                    $this->count['concreteClasses']++;
                                }
                            }
                        }
                    }
                    break;

                    case T_FUNCTION: {
                        $currentBlock = T_FUNCTION;

                        if (is_array($tokens[$i+2]) &&
                            $tokens[$i+2][0] == T_STRING) {
                            $functionName = $tokens[$i+2][1];
                        }

                        else if ($tokens[$i+2] == '&' &&
                                 is_array($tokens[$i+3]) &&
                                 $tokens[$i+3][0] == T_STRING) {
                            $functionName = $tokens[$i+3][1];
                        }

                        else {
                            $currentBlock = 'anonymous function';
                            $functionName = 'anonymous function';
                            $this->count['anonymousFunctions']++;
                        }

                        if ($currentBlock == T_FUNCTION) {
                            if ($className === NULL) {
                                $this->count['functions']++;
                            } else {
                                $static     = FALSE;
                                $visibility = T_PUBLIC;

                                for ($j = $i; $j > 0; $j--) {
                                    if (is_string($tokens[$j])) {
                                        if ($tokens[$j] == '{' || $tokens[$j] == '}') {
                                            break;
                                        }

                                        continue;
                                    }

                                    if (isset($tokens[$j][0])) {
                                        switch ($tokens[$j][0]) {
                                            case T_PRIVATE: {
                                                $visibility = T_PRIVATE;
                                            }
                                            break;

                                            case T_PROTECTED: {
                                                $visibility = T_PROTECTED;
                                            }
                                            break;

                                            case T_STATIC: {
                                                $static = TRUE;
                                            }
                                            break;
                                        }
                                    }
                                }

                                if ($testClass &&
                                    $this->isTestMethod($functionName, $visibility, $static, $tokens, $i)) {
                                    $this->count['testMethods']++;
                                }

                                else if (!$testClass) {
                                    if ($static) {
                                        $this->count['staticMethods']++;
                                    } else {
                                        $this->count['nonStaticMethods']++;
                                    }

                                    if ($visibility != T_PUBLIC) {
                                        $this->count['nonPublicMethods']++;
                                    }
                                }
                            }
                        }
                    }
                    break;

                    case T_CURLY_OPEN: {
                        $currentBlock = T_CURLY_OPEN;
                        array_push($blocks, $currentBlock);
                    }
                    break;

                    case T_DOLLAR_OPEN_CURLY_BRACES: {
                        $currentBlock = T_DOLLAR_OPEN_CURLY_BRACES;
                        array_push($blocks, $currentBlock);
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
                        if (!$testClass) {
                            if ($className !== NULL) {
                                $this->count['ccnMethods']++;
                            }

                            $this->count['ccn']++;
                        }
                    }
                    break;

                    case T_COMMENT:
                    case T_DOC_COMMENT: {
                        $cloc += substr_count($value, "\n") + 1;
                    }
                    break;

                    case T_CONST: {
                        $this->count['classConstants']++;
                    }
                    break;

                    case T_STRING: {
                        if ($value == 'define') {
                            $this->count['globalConstants']++;
                        }
                    }
                    break;
                }
            }

            $this->count['loc']          += $loc;
            $this->count['nclocClasses'] += $nclocClasses;
            $this->count['cloc']         += $cloc;
            $this->count['ncloc']        += $loc - $cloc;
            $this->count['files']++;

            if (function_exists('bytekit_disassemble_file')) {
                $this->count['eloc'] += $this->countEloc($filename, $loc);
            }
        }

        protected function isTestMethod(
            $functionName,
            $visibility,
            $static,
            array $tokens,
            $currentToken
        ) {
            if ($static || $visibility != T_PUBLIC) {
                return false;
            }

            if (strpos($functionName, 'test') === 0) {
                return true;
            }

            while ($tokens[$currentToken][0] != T_DOC_COMMENT) {
                if ($tokens[$currentToken] == '{' || $tokens[$currentToken] == '}') {
                    return false;
                }

                --$currentToken;
            }

            return strpos($tokens[$currentToken][1], '@test') !== false ||
                   strpos($tokens[$currentToken][1], '@scenario') !== false;
        }

        /**
         * Counts the Executable Lines of Code (ELOC) using Bytekit.
         *
         * @param  string  $filename
         * @param  integer $loc
         * @return integer
         * @since  Method available since Release 1.1.0
         */
        protected function countEloc($filename, $loc)
        {
            $bytecode = @bytekit_disassemble_file($filename);

            if (!is_array($bytecode)) {
                return 0;
            }

            $lines = array();

            foreach ($bytecode['functions'] as $function) {
                foreach ($function['raw']['opcodes'] as $opline) {
                    if ($opline['lineno'] <= $loc &&
                        !isset($this->opcodeBlacklist[$opline['opcode']]) &&
                        !isset($lines[$opline['lineno']])) {
                        $lines[$opline['lineno']] = TRUE;
                    }
                }
            }

            return count($lines);
        }

        /**
         * @param  array   $tokens
         * @param  integer $i
         * @return string
         * @since  Method available since Release 1.3.0
         */
        protected function getNamespaceName(array $tokens, $i)
        {
            if (isset($tokens[$i+2][1])) {
                $namespace = $tokens[$i+2][1];

                for ($j = $i+3; ; $j += 2) {
                    if (isset($tokens[$j]) && $tokens[$j][0] == T_NS_SEPARATOR) {
                        $namespace .= '\\' . $tokens[$j+1][1];
                    } else {
                        break;
                    }
                }

                return $namespace;
            }

            return FALSE;
        }

        /**
         * @param  string  $namespace
         * @param  array   $tokens
         * @param  integer $i
         * @return string
         * @since  Method available since Release 1.3.0
         */
        protected function getClassName($namespace, array $tokens, $i)
        {
            $i         += 2;
            $namespaced = FALSE;
            $className  = $tokens[$i][1];

            if ($className === '\\') {
                $namespaced = TRUE;
            }

            while (is_array($tokens[$i+1]) && $tokens[$i+1][0] !== T_WHITESPACE) {
                $className .= $tokens[++$i][1];
            }

            if (!$namespaced && $namespace !== FALSE) {
                $className = $namespace . '\\' . $className;
            }

            return $className;
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
                if ($parent == 'PHPUnit_Framework_TestCase' ||
                    $parent == '\\PHPUnit_Framework_TestCase') {
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
}
