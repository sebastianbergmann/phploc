<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\PHPLOC;

/**
 * PHPLOC code analyser.
 *
 * @since     Class available since Release 1.0.0
 */
class Analyser
{
    /**
     * @var Collector
     */
    private $collector;

    /**
     * @var array
     */
    private $namespaces = [];

    /**
     * @var array
     */
    private $classes = [];

    /**
     * @var array
     */
    private $constants = [];

    /**
     * @var array
     */
    private $possibleConstantAccesses = [];

    /**
     * @var array
     */
    private $count = [
        'interfaces'                  => 0,
        'traits'                      => 0,
        'classes'                     => 0,
        'abstractClasses'             => 0,
        'concreteClasses'             => 0,
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
    ];

    /**
     * @var array
     */
    private $superGlobals = [
        '$_ENV'             => true,
        '$_POST'            => true,
        '$_GET'             => true,
        '$_COOKIE'          => true,
        '$_SERVER'          => true,
        '$_FILES'           => true,
        '$_REQUEST'         => true,
        '$HTTP_ENV_VARS'    => true,
        '$HTTP_POST_VARS'   => true,
        '$HTTP_GET_VARS'    => true,
        '$HTTP_COOKIE_VARS' => true,
        '$HTTP_SERVER_VARS' => true,
        '$HTTP_POST_FILES'  => true
    ];

    public function __construct()
    {
        $this->collector = new Collector();
    }

    /**
     * Processes a set of files.
     *
     * @param  array $files
     * @param  bool  $countTests
     * @return array
     * @since  Method available since Release 1.2.0
     */
    public function countFiles(array $files, $countTests)
    {
        foreach ($files as $file) {
            $this->countFile($file, $countTests);
        }

        $count = $this->count;

        $count['namespaces']        = count($this->namespaces);
        $count['classes']           = $count['abstractClasses'] +
                                      $count['concreteClasses'];
        $count['constants']         = $count['classConstants'] +
                                      $count['globalConstants'];
        $count['attributeAccesses'] = $count['staticAttributeAccesses'] +
                                      $count['instanceAttributeAccesses'];
        $count['methodCalls']       = $count['staticMethodCalls'] +
                                      $count['instanceMethodCalls'];

        foreach ($this->possibleConstantAccesses as $possibleConstantAccess) {
            if (in_array($possibleConstantAccess, $this->constants)) {
                $count['globalConstantAccesses']++;
            }
        }

        $count['globalAccesses'] = $count['globalConstantAccesses'] +
                                   $count['globalVariableAccesses'] +
                                   $count['superGlobalVariableAccesses'];

        return array_merge($count, $this->collector->getPublisher()->toArray());
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
        $namespace = false;

        for ($i = 0; $i < $numTokens; $i++) {
            if (is_string($tokens[$i])) {
                continue;
            }

            switch ($tokens[$i][0]) {
                case T_NAMESPACE:
                    $namespace = $this->getNamespaceName($tokens, $i);
                    break;

                case T_CLASS:
                    if (!$this->isClassDeclaration($tokens, $i)) {
                        continue;
                    }

                    $className = $this->getClassName($namespace, $tokens, $i);

                    if (isset($tokens[$i+4]) && is_array($tokens[$i+4]) &&
                        $tokens[$i+4][0] == T_EXTENDS) {
                        $parent = $this->getClassName($namespace, $tokens, $i + 4);
                    } else {
                        $parent = null;
                    }

                    $this->classes[$className] = $parent;
                    break;
            }
        }
    }

    /**
     * Processes a single file.
     *
     * @param string $filename
     * @param bool   $countTests
     */
    public function countFile($filename, $countTests)
    {
        if ($countTests) {
            $this->preProcessFile($filename);
        }

        $buffer              = file_get_contents($filename);
        $this->collector->incrementLines(substr_count($buffer, "\n"));
        $tokens              = token_get_all($buffer);
        $numTokens           = count($tokens);

        unset($buffer);

        $this->collector->addFile($filename);

        $blocks            = [];
        $currentBlock      = false;
        $namespace         = false;
        $className         = null;
        $functionName      = null;
        $testClass         = false;
        $currentClassData  = null;
        $currentMethodData = null;

        for ($i = 0; $i < $numTokens; $i++) {
            if (is_string($tokens[$i])) {
                $token = trim($tokens[$i]);

                if ($token == ';') {
                    if ($className !== null && !$testClass) {
                        $currentClassData['lloc']++;

                        if ($functionName !== null) {
                            $currentMethodData['lloc']++;
                        }
                    } elseif ($functionName !== null) {
                        $this->collector->incrementFunctionLines();
                    }

                    $this->collector->incrementLogicalLines();
                } elseif ($token == '?' && !$testClass) {
                    if ($className !== null) {
                        $this->collector->incrementMethodComplexity();
                        $currentClassData['ccn']++;
                        $currentMethodData['ccn']++;
                    }

                    $this->collector->incrementComplexity();
                } elseif ($token == '{') {
                    if ($currentBlock == T_CLASS) {
                        $block = $className;
                    } elseif ($currentBlock == T_FUNCTION) {
                        $block = $functionName;
                    } else {
                        $block = false;
                    }

                    array_push($blocks, $block);

                    $currentBlock = false;
                } elseif ($token == '}') {
                    $block = array_pop($blocks);

                    if ($block !== false && $block !== null) {
                        if ($block == $functionName) {
                            $functionName = null;

                            if ($currentMethodData !== null) {
                                $this->collector->addMethodComplexity($currentMethodData['ccn']);
                                $this->collector->addMethodLines($currentMethodData['lloc']);
                                $currentMethodData  = null;
                            }
                        } elseif ($block == $className) {
                            $className         = null;
                            $testClass         = false;
                            $this->collector->addClassComplexity($currentClassData['ccn']);
                            $this->collector->addClassLines($currentClassData['lloc']);
                            $currentClassData  = null;
                        }
                    }
                }

                continue;
            }

            list ($token, $value) = $tokens[$i];

            switch ($token) {
                case T_NAMESPACE:
                    $namespace = $this->getNamespaceName($tokens, $i);

                    if (!isset($this->namespaces[$namespace])) {
                        $this->namespaces[$namespace] = true;
                    }
                    break;

                case T_CLASS:
                case T_INTERFACE:
                case T_TRAIT:
                    if (!$this->isClassDeclaration($tokens, $i)) {
                        continue;
                    }

                    $currentClassData = ['ccn' => 1, 'lloc' => 0];
                    $className        = $this->getClassName($namespace, $tokens, $i);
                    $currentBlock     = T_CLASS;

                    if ($token == T_TRAIT) {
                        $this->count['traits']++;
                    } elseif ($token == T_INTERFACE) {
                        $this->count['interfaces']++;
                    } else {
                        if ($countTests && $this->isTestClass($className)) {
                            $testClass = true;
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
                    break;

                case T_FUNCTION:
                    $prev = $this->getPreviousNonWhitespaceTokenPos($tokens, $i);

                    if ($tokens[$prev][0] === T_USE) {
                        continue;
                    }

                    $currentBlock = T_FUNCTION;

                    $next = $this->getNextNonWhitespaceTokenPos($tokens, $i);

                    if (!is_array($tokens[$next]) && $tokens[$next] == '&') {
                        $next = $this->getNextNonWhitespaceTokenPos($tokens, $next);
                    }

                    if (is_array($tokens[$next]) &&
                        $tokens[$next][0] == T_STRING) {
                        $functionName = $tokens[$next][1];
                    } else {
                        $currentBlock = 'anonymous function';
                        $functionName = 'anonymous function';
                        $this->collector->incrementAnonymousFunctions();
                    }

                    if ($currentBlock == T_FUNCTION) {
                        if ($className === null &&
                            $functionName != 'anonymous function') {
                            $this->collector->incrementNamedFunctions();
                        } else {
                            $static     = false;
                            $visibility = T_PUBLIC;

                            for ($j = $i; $j > 0; $j--) {
                                if (is_string($tokens[$j])) {
                                    if ($tokens[$j] == '{' ||
                                        $tokens[$j] == '}' ||
                                        $tokens[$j] == ';') {
                                        break;
                                    }

                                    continue;
                                }

                                if (isset($tokens[$j][0])) {
                                    switch ($tokens[$j][0]) {
                                        case T_PRIVATE:
                                            $visibility = T_PRIVATE;
                                            break;

                                        case T_PROTECTED:
                                            $visibility = T_PROTECTED;
                                            break;

                                        case T_STATIC:
                                            $static = true;
                                            break;
                                    }
                                }
                            }

                            if ($testClass &&
                                $this->isTestMethod($functionName, $visibility, $static, $tokens, $i)) {
                                $this->count['testMethods']++;
                            } elseif (!$testClass) {
                                $currentMethodData = ['ccn' => 1, 'lloc' => 0];

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
                    break;

                case T_CURLY_OPEN:
                    $currentBlock = T_CURLY_OPEN;
                    array_push($blocks, $currentBlock);
                    break;

                case T_DOLLAR_OPEN_CURLY_BRACES:
                    $currentBlock = T_DOLLAR_OPEN_CURLY_BRACES;
                    array_push($blocks, $currentBlock);
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
                case T_LOGICAL_OR:
                    if (!$testClass) {
                        if ($currentMethodData !== null) {
                            $this->collector->incrementMethodComplexity();
                            $currentClassData['ccn']++;
                            $currentMethodData['ccn']++;
                        }

                        $this->collector->incrementComplexity();
                    }
                    break;

                case T_COMMENT:
                case T_DOC_COMMENT:
                    // We want to count all intermediate lines before the token ends
                    // But sometimes a new token starts after a newline, we don't want to count that.
                    // That happend with /* */ and /**  */, but not with // since it'll end at the end
                    $this->collector->incrementCommentLines(substr_count(rtrim($value, "\n"), "\n") + 1);
                    break;
                case T_CONST:
                    $this->count['classConstants']++;
                    break;

                case T_STRING:
                    if ($value == 'define') {
                        $this->count['globalConstants']++;

                        $j = $i + 1;

                        while (isset($tokens[$j]) && $tokens[$j] != ';') {
                            if (is_array($tokens[$j]) &&
                                $tokens[$j][0] == T_CONSTANT_ENCAPSED_STRING) {
                                $this->constants[] = str_replace('\'', '', $tokens[$j][1]);

                                break;
                            }

                            $j++;
                        }
                    } else {
                        $this->possibleConstantAccesses[] = $value;
                    }
                    break;

                case T_DOUBLE_COLON:
                case T_OBJECT_OPERATOR:
                    $n  = $this->getNextNonWhitespaceTokenPos($tokens, $i);
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
                        } elseif ($token == T_OBJECT_OPERATOR) {
                            $this->count['instanceAttributeAccesses']++;
                        }
                    }
                    break;

                case T_GLOBAL:
                    $this->count['globalVariableAccesses']++;
                    break;

                case T_VARIABLE:
                    if ($value == '$GLOBALS') {
                        $this->count['globalVariableAccesses']++;
                    } elseif (isset($this->superGlobals[$value])) {
                        $this->count['superGlobalVariableAccesses']++;
                    }
                    break;
            }
        }
    }

    /**
     * @param  array  $tokens
     * @param  int    $i
     * @return string
     * @since  Method available since Release 1.3.0
     */
    private function getNamespaceName(array $tokens, $i)
    {
        if (isset($tokens[$i+2][1])) {
            $namespace = $tokens[$i+2][1];

            for ($j = $i+3;; $j += 2) {
                if (isset($tokens[$j]) && $tokens[$j][0] == T_NS_SEPARATOR) {
                    $namespace .= '\\' . $tokens[$j+1][1];
                } else {
                    break;
                }
            }

            return $namespace;
        }

        return false;
    }

    /**
     * @param  string $namespace
     * @param  array  $tokens
     * @param  int    $i
     * @return string
     * @since  Method available since Release 1.3.0
     */
    private function getClassName($namespace, array $tokens, $i)
    {
        $i += 2;
        if (!isset($tokens[$i][1])) {
            return 'invalid class name';
        }
        $className  = $tokens[$i][1];

        $namespaced = $className === '\\';

        while (is_array($tokens[$i+1]) && $tokens[$i+1][0] !== T_WHITESPACE) {
            $className .= $tokens[++$i][1];
        }

        if (!$namespaced && $namespace !== false) {
            $className = $namespace . '\\' . $className;
        }

        return strtolower($className);
    }

    /**
     * @param  string $className
     * @return bool
     * @since  Method available since Release 1.2.0
     */
    private function isTestClass($className)
    {
        $parent = $this->classes[$className];
        $count  = 0;

        // Check ancestry for PHPUnit_Framework_TestCase.
        while ($parent !== null) {
            $count++;

            if ($count > 100) {
                // Prevent infinite loops and just bail
                break;
            }

            if ($parent == 'phpunit_framework_testcase' ||
                $parent == '\\phpunit_framework_testcase') {
                return true;
            }

            if (isset($this->classes[$parent]) && $parent !== $this->classes[$parent]) {
                $parent = $this->classes[$parent];
            } else {
                // Class has a parent that is declared in a file
                // that was not pre-processed.
                break;
            }
        }

        // Fallback: Treat the class as a test case class if the name
        // of the parent class ends with "TestCase".
        return substr($this->classes[$className], -8) === 'testcase';
    }

    /**
     * @param  string $functionName
     * @param  int    $visibility
     * @param  bool   $static
     * @param  array  $tokens
     * @param  int    $currentToken
     * @return bool
     * @since  Method available since Release 2.0.0
     */
    private function isTestMethod($functionName, $visibility, $static, array $tokens, $currentToken)
    {
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
     * @param  array $tokens
     * @param  int   $start
     * @return bool
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

        return false;
    }

    /**
     * @param  array $tokens
     * @param  int   $start
     * @return bool
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

        return false;
    }

    /**
     * @param  array $tokens
     * @param  int   $i
     * @return bool
     */
    private function isClassDeclaration(array $tokens, $i)
    {
        $n = $this->getPreviousNonWhitespaceTokenPos($tokens, $i);

        if (isset($tokens[$n]) && is_array($tokens[$n]) &&
            $tokens[$n][0] == T_DOUBLE_COLON) {
            return false;
        }

        return true;
    }
}
