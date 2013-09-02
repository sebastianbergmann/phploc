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
    // @codeCoverageIgnoreStart
    if (!defined('T_TRAIT')) {
        define('T_TRAIT', 1000);
    }
    // @codeCoverageIgnoreEnd

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
        private $namespaces = array();

        /**
         * @var array
         */
        private $classes = array();

        /**
         * @var array
         */
        private $constants = array();

        /**
         * @var array
         */
        private $possibleConstantAccesses = array();

        /**
         * @var array
         */
        private $count = array(
          'files'                       => 0,
          'loc'                         => 0,
          'lloc'                        => 0,
          'llocClasses'                 => 0,
          'llocFunctions'               => 0,
          'llocGlobal'                  => 0,
          'cloc'                        => 0,
          'ccn'                         => 0,
          'ccnMethods'                  => 0,
          'interfaces'                  => 0,
          'traits'                      => 0,
          'classes'                     => 0,
          'abstractClasses'             => 0,
          'concreteClasses'             => 0,
          'functions'                   => 0,
          'namedFunctions'              => 0,
          'anonymousFunctions'          => 0,
          'methods'                     => 0,
          'publicMethods'               => 0,
          'nonPublicMethods'            => 0,
          'nonStaticMethods'            => 0,
          'staticMethods'               => 0,
          'constants'                   => 0,
          'classConstants'              => 0,
          'globalConstants'             => 0,
          'testClasses'                 => 0,
          'testMethods'                 => 0,
          'ccnByLloc'                   => 0,
          'ccnByNom'                    => 0,
          'llocByNoc'                   => 0,
          'llocByNom'                   => 0,
          'llocByNof'                   => 0,
          'methodCalls'                 => 0,
          'staticMethodCalls'           => 0,
          'instanceMethodCalls'         => 0,
          'attributeAccesses'           => 0,
          'staticAttributeAccesses'     => 0,
          'instanceAttributeAccesses'   => 0,
          'globalAccesses'              => 0,
          'globalVariableAccesses'      => 0,
          'superGlobalVariableAccesses' => 0,
          'globalConstantAccesses'      => 0,
        );

        /**
         * @var array
         */
        private $superGlobals = array(
          '$_ENV' => TRUE,
          '$_POST' => TRUE,
          '$_GET' => TRUE,
          '$_COOKIE' => TRUE,
          '$_SERVER' => TRUE,
          '$_FILES' => TRUE,
          '$_REQUEST' => TRUE,
          '$HTTP_ENV_VARS' => TRUE,
          '$HTTP_POST_VARS' => TRUE,
          '$HTTP_GET_VARS' => TRUE,
          '$HTTP_COOKIE_VARS' => TRUE,
          '$HTTP_SERVER_VARS' => TRUE,
          '$HTTP_POST_FILES' => TRUE
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
                    $this->preProcessFile($file);
                }
            }

            $directories = array();

            foreach ($files as $file) {
                $directory = dirname($file);

                if (!isset($directories[$directory])) {
                    $directories[$directory] = TRUE;
                }

                $this->countFile($file, $countTests);
            }

            $count = $this->count;

            $count['directories']       = count($directories) - 1;
            $count['namespaces']        = count($this->namespaces);
            $count['classes']           = $count['abstractClasses'] +
                                          $count['concreteClasses'];
            $count['functions']         = $count['namedFunctions'] +
                                          $count['anonymousFunctions'];
            $count['constants']         = $count['classConstants'] +
                                          $count['globalConstants'];
            $count['attributeAccesses'] = $count['staticAttributeAccesses'] +
                                          $count['instanceAttributeAccesses'];
            $count['methodCalls']       = $count['staticMethodCalls'] +
                                          $count['instanceMethodCalls'];
            $count['llocGlobal']        = $count['lloc'] -
                                          $count['llocClasses'] -
                                          $count['llocFunctions'];
            $count['ncloc']             = $count['loc'] - $count['cloc'];

            foreach ($this->possibleConstantAccesses as $possibleConstantAccess) {
                if (in_array($possibleConstantAccess, $this->constants)) {
                    $count['globalConstantAccesses']++;
                }
            }

            $count['globalAccesses'] = $count['globalConstantAccesses'] +
                                       $count['globalVariableAccesses'] +
                                       $count['superGlobalVariableAccesses'];

            if ($count['lloc'] > 0) {
                $count['ccnByLloc'] = $count['ccn'] / $count['lloc'];
            }

            if ($count['methods'] > 0) {
                $count['ccnByNom'] = ($count['methods'] +
                                      $count['ccnMethods']) /
                                     $count['methods'];
            }

            if ($count['classes'] > 0) {
                $count['llocByNoc'] = $count['llocClasses'] / $count['classes'];
            }

            if ($count['methods'] > 0) {
                $count['llocByNom'] = $count['llocClasses'] / $count['methods'];
            }

            if ($count['functions'] > 0) {
                $count['llocByNof'] = $count['llocFunctions'] / $count['functions'];
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

                switch ($tokens[$i][0]) {
                    case T_NAMESPACE: {
                        $namespace = $this->getNamespaceName($tokens, $i);
                    }
                    break;

                    case T_CLASS: {
                        if (!$this->isClassDeclaration($tokens, $i)) {
                            continue;
                        }

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
            $buffer              = file_get_contents($filename);
            $this->count['loc'] += substr_count($buffer, "\n");
            $tokens              = token_get_all($buffer);
            $numTokens           = count($tokens);

            unset($buffer);

            $this->count['files']++;

            $blocks       = array();
            $currentBlock = FALSE;
            $namespace    = FALSE;
            $className    = NULL;
            $functionName = NULL;
            $testClass    = FALSE;

            for ($i = 0; $i < $numTokens; $i++) {
                if (is_string($tokens[$i])) {
                    $token = trim($tokens[$i]);

                    if ($token == ';') {
                        if ($className !== NULL && !$testClass) {
                            $this->count['llocClasses']++;
                        }

                        else if ($functionName !== NULL) {
                            $this->count['llocFunctions']++;
                        }

                        $this->count['lloc']++;
                    }

                    else if ($token == '?' && !$testClass) {
                        if ($className !== NULL) {
                            $this->count['ccnMethods']++;
                        }

                        $this->count['ccn']++;
                    }

                    else if ($token == '{') {
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

                    else if ($token == '}') {
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
                        if (!$this->isClassDeclaration($tokens, $i)) {
                            continue;
                        }

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
                            if ($className === NULL &&
                                $functionName != 'anonymous function') {
                                $this->count['namedFunctions']++;
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
                                    if (!$static) {
                                        $this->count['nonStaticMethods']++;
                                    } else {
                                        $this->count['staticMethods']++;
                                    }

                                    if ($visibility == T_PUBLIC) {
                                        $this->count['publicMethods']++;
                                    } else {
                                        $this->count['nonPublicMethods']++;
                                    }

                                    $this->count['methods']++;
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

                    case T_COMMENT: {
                        $this->count['cloc']++;
                    }
                    break;

                    case T_DOC_COMMENT: {
                        $this->count['cloc'] += substr_count($value, "\n") + 1;
                    }
                    break;

                    case T_CONST: {
                        $this->count['classConstants']++;
                    }
                    break;

                    case T_STRING: {
                        if ($value == 'define') {
                            $this->count['globalConstants']++;

                            $j = $i + 1;

                            while (isset($tokens[$j]) && $tokens[$j] != ';') {
                                if (is_array($tokens[$j]) &&
                                    $tokens[$j][0] == T_CONSTANT_ENCAPSED_STRING) {
                                    $this->constants[] = str_replace(
                                      '\'', '', $tokens[$j][1]
                                    );

                                    break;
                                }

                                $j++;
                            }
                        }

                        else {
                            $this->possibleConstantAccesses[] = $value;
                        }
                    }
                    break;

                    case T_DOUBLE_COLON:
                    case T_OBJECT_OPERATOR: {
                        $n = $this->getNextNonWhitespaceTokenPos($tokens, $i);
                        $nn = $this->getNextNonWhitespaceTokenPos($tokens, $n);

                        if ($n && $nn &&
                            isset($tokens[$n][0]) &&
                            ($tokens[$n][0] == T_STRING ||
                             $tokens[$n][0] == T_VARIABLE) &&
                            $tokens[$nn] == '(') {
                            if ($token == T_DOUBLE_COLON) {
                                $this->count['staticMethodCalls']++;
                            } else {
                                $this->count['instanceMethodCalls']++;
                            }
                        } else {
                            if ($token == T_DOUBLE_COLON &&
                                $tokens[$n][0] == T_VARIABLE) {
                                $this->count['staticAttributeAccesses']++;
                            }

                            else if ($token == T_OBJECT_OPERATOR) {
                                $this->count['instanceAttributeAccesses']++;
                            }
                        }
                    }
                    break;

                    case T_GLOBAL: {
                        $this->count['globalVariableAccesses']++;
                    }
                    break;

                    case T_VARIABLE: {
                        if ($value == '$GLOBALS') {
                            $this->count['globalVariableAccesses']++;
                        }

                        else if (isset($this->superGlobals[$value])) {
                            $this->count['superGlobalVariableAccesses']++;
                        }
                    }
                    break;
                }
            }
        }

        /**
         * @param  array   $tokens
         * @param  integer $i
         * @return string
         * @since  Method available since Release 1.3.0
         */
        private function getNamespaceName(array $tokens, $i)
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
        private function getClassName($namespace, array $tokens, $i)
        {
            $i         += 2;
            $namespaced = FALSE;

            if (!isset($tokens[$i][1])) {
                return 'invalid class name';
            }

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
        private function isTestClass($className)
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

        /**
         * @param  string  $functionName
         * @param  integer $visibility
         * @param  boolean $static
         * @param  array   $tokens
         * @param  integer $currentToken
         * @return boolean
         * @since  Method available since Release 2.0.0
         */
        private function isTestMethod($functionName, $visibility, $static, array $tokens, $currentToken) {
            if ($static || $visibility != T_PUBLIC) {
                return FALSE;
            }

            if (strpos($functionName, 'test') === 0) {
                return TRUE;
            }

            while ($tokens[$currentToken][0] != T_DOC_COMMENT) {
                if ($tokens[$currentToken] == '{' || $tokens[$currentToken] == '}') {
                    return FALSE;
                }

                --$currentToken;
            }

            return strpos($tokens[$currentToken][1], '@test') !== FALSE ||
                   strpos($tokens[$currentToken][1], '@scenario') !== FALSE;
        }

        /**
         * @param  array   $tokens
         * @param  integer $start
         * @return boolean
         */
        private function getNextNonWhitespaceTokenPos(array $tokens, $start)
        {
            if (isset($tokens[$start+1])) {
                if (isset($tokens[$start+1][0]) &&
                    $tokens[$start+1][0] == T_WHITESPACE &&
                    isset($tokens[$start+2])) {
                    return $start + 2;
                } else {
                    return $start + 1;
                }
            }

            return FALSE;
        }

        /**
         * @param  array   $tokens
         * @param  integer $start
         * @return boolean
         */
        private function getPreviousNonWhitespaceTokenPos(array $tokens, $start)
        {
            if (isset($tokens[$start-1])) {
                if (isset($tokens[$start-1][0]) &&
                    $tokens[$start-1][0] == T_WHITESPACE &&
                    isset($tokens[$start-2])) {
                    return $start - 2;
                } else {
                    return $start - 1;
                }
            }

            return FALSE;
        }

        private function isClassDeclaration($tokens, $i)
        {
            $n = $this->getPreviousNonWhitespaceTokenPos($tokens, $i);

            if (isset($tokens[$n]) && is_array($tokens[$n]) &&
                $tokens[$n][0] == T_DOUBLE_COLON) {
                return FALSE;
            }

            return TRUE;
        }
    }
}
