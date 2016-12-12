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
