<?php

namespace SebastianBergmann\PHPLOC;

class Collector
{
    private $counts = [];

    public function getPublisher()
    {
        return new Publisher($this->counts);
    }

    public function addFile($filename)
    {
        $this->increment('files');
        $this->addUnique('directories', dirname($filename));
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

    public function addClassLines($number)
    {
        $this->addToArray('class lines', $number);
    }

    public function addMethodLines($number)
    {
        $this->addToArray('method lines', $number);
    }

    public function incrementFunctionLines()
    {
        $this->increment('function lines');
    }

    public function addClassComplexity($complexity)
    {
        $this->addToArray('class complexity', $complexity);
    }

    public function addMethodComplexity($complexity)
    {
        $this->addToArray('method complexity', $complexity);
    }

    public function incrementComplexity()
    {
        $this->increment('complexity');
    }

    public function incrementMethodComplexity()
    {
        $this->increment('total method complexity');
    }

    public function incrementGlobalConstantAccesses()
    {
        $this->increment('global constant accesses');
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

    public function incrementNamedFunctions()
    {
        $this->increment('named functions');
    }

    public function incrementAnonymousFunctions()
    {
        $this->increment('anonymous functions');
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
