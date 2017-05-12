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

class Collector
{
    private $counts = [];

    private $currentClassComplexity = 0;

    private $currentClassLines = 0;

    private $currentMethodComplexity = 0;

    private $currentMethodLines = 0;

    public function getPublisher()
    {
        return new Publisher($this->counts);
    }

    public function addFile($filename)
    {
        $this->increment('files');
        $this->addUnique('directories', \dirname($filename));
    }

    public function incrementLines($number)
    {
        $this->increment('lines', $number);
    }

    public function incrementCommentLines($number)
    {
        $this->increment('comment lines', $number);
    }

    public function incrementLogicalLines()
    {
        $this->increment('logical lines');
    }

    public function currentClassReset()
    {
        if ($this->currentClassComplexity > 0) {
            $this->addToArray('class complexity', $this->currentClassComplexity);
            $this->addToArray('class lines', $this->currentClassLines);
        }
        $this->currentClassComplexity = 0;
        $this->currentClassLines      = 0;
    }

    public function currentClassIncrementComplexity()
    {
        $this->currentClassComplexity++;
    }

    public function currentClassIncrementLines()
    {
        $this->currentClassLines++;
    }

    public function currentMethodStart()
    {
        $this->currentMethodComplexity = 1;
        $this->currentMethodLines      = 0;
    }

    public function currentMethodIncrementComplexity()
    {
        $this->currentMethodComplexity++;
        $this->increment('total method complexity');
    }

    public function currentMethodIncrementLines()
    {
        $this->currentMethodLines++;
    }

    public function currentMethodStop()
    {
        $this->addToArray('method complexity', $this->currentMethodComplexity);
        $this->addToArray('method lines', $this->currentMethodLines);
    }

    public function incrementFunctionLines()
    {
        $this->increment('function lines');
    }

    public function incrementComplexity()
    {
        $this->increment('complexity');
    }

    public function addPossibleConstantAccesses($name)
    {
        $this->addToArray('possible constant accesses', $name);
    }

    public function addConstant($name)
    {
        $this->addToArray('constant', $name);
    }

    public function incrementGlobalVariableAccesses()
    {
        $this->increment('global variable accesses');
    }

    public function incrementSuperGlobalVariableAccesses()
    {
        $this->increment('super global variable accesses');
    }

    public function incrementNonStaticAttributeAccesses()
    {
        $this->increment('non-static attribute accesses');
    }

    public function incrementStaticAttributeAccesses()
    {
        $this->increment('static attribute accesses');
    }

    public function incrementNonStaticMethodCalls()
    {
        $this->increment('non-static method calls');
    }

    public function incrementStaticMethodCalls()
    {
        $this->increment('static method calls');
    }

    public function addNamespace($namespace)
    {
        $this->addUnique('namespaces', $namespace);
    }

    public function incrementInterfaces()
    {
        $this->increment('interfaces');
    }

    public function incrementTraits()
    {
        $this->increment('traits');
    }

    public function incrementAbstractClasses()
    {
        $this->increment('abstract classes');
    }

    public function incrementConcreteClasses()
    {
        $this->increment('concrete classes');
    }

    public function incrementNonStaticMethods()
    {
        $this->increment('non-static methods');
    }

    public function incrementStaticMethods()
    {
        $this->increment('static methods');
    }

    public function incrementPublicMethods()
    {
        $this->increment('public methods');
    }

    public function incrementNonPublicMethods()
    {
        $this->increment('non-public methods');
    }

    public function incrementNamedFunctions()
    {
        $this->increment('named functions');
    }

    public function incrementAnonymousFunctions()
    {
        $this->increment('anonymous functions');
    }

    public function incrementGlobalConstants()
    {
        $this->increment('global constants');
    }

    public function incrementClassConstants()
    {
        $this->increment('class constants');
    }

    public function incrementTestClasses()
    {
        $this->increment('test classes');
    }

    public function incrementTestMethods()
    {
        $this->increment('test methods');
    }

    private function addUnique($key, $name)
    {
        $this->check($key, []);
        $this->counts[$key][$name] = true;
    }

    private function addToArray($key, $value)
    {
        $this->check($key, []);
        $this->counts[$key][] = $value;
    }

    private function increment($key, $number = 1)
    {
        $this->check($key, 0);
        $this->counts[$key] += $number;
    }

    private function check($key, $default)
    {
        if (!isset($this->counts[$key])) {
            $this->counts[$key] = $default;
        }
    }
}
