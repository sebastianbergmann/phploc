<?php

namespace SebastianBergmann\PHPLOC;

class Publisher
{
    private $counts;

    public function __construct(array $counts)
    {
        $this->counts = $counts;
    }

    public function getDirectories()
    {
        return $this->getCount('directories') - 1;
    }

    public function getFiles()
    {
        return $this->getValue('files');
    }

    public function getLines()
    {
        return $this->getValue('lines');
    }

    public function getCommentLines()
    {
        return $this->getValue('comment lines');
    }

    public function getNonCommentLines()
    {
        return $this->getLines() - $this->getCommentLines();
    }

    public function getLogicalLines()
    {
        return $this->getValue('logical lines');
    }

    public function getClassLines()
    {
        return $this->getSum('class lines');
    }

    public function getAverageClassLength()
    {
        return $this->getAverage('class lines');
    }

    public function getMinimumClassLength()
    {
        return $this->getMinimum('class lines');
    }

    public function getMaximumClassLength()
    {
        return $this->getMaximum('class lines');
    }

    public function getAverageMethodLength()
    {
        return $this->getAverage('method lines');
    }

    public function getMinimumMethodLength()
    {
        return $this->getMinimum('method lines');
    }

    public function getMaximumMethodLength()
    {
        return $this->getMaximum('method lines');
    }

    public function getFunctionLines()
    {
        return $this->getValue('function lines');
    }

    public function getAverageFunctionLength()
    {
        return $this->divide($this->getFunctionLines(), $this->getFunctions());
    }

    public function getNotInClassesOrFunctions()
    {
        return $this->getLogicalLines() - $this->getClassLines() - $this->getFunctionLines();
    }

    public function getComplexity()
    {
        return $this->getValue('complexity');
    }

    public function getMethodComplexity()
    {
        return $this->getValue('total method complexity');
    }

    public function getAverageComplexityPerLogicalLine()
    {
        return $this->divide($this->getComplexity(), $this->getLogicalLines());
    }

    public function getAverageComplexityPerClass()
    {
        return $this->getAverage('class complexity');
    }

    public function getMinimumClassComplexity()
    {
        return $this->getMinimum('class complexity');
    }

    public function getMaximumClassComplexity()
    {
        return $this->getMaximum('class complexity');
    }

    public function getAverageComplexityPerMethod()
    {
        return $this->getAverage('method complexity');
    }

    public function getMinimumMethodComplexity()
    {
        return $this->getMinimum('method complexity');
    }

    public function getMaximumMethodComplexity()
    {
        return $this->getMaximum('method complexity');
    }

    public function getFunctions()
    {
        return $this->getNamedFunctions() + $this->getAnonymousFunctions();
    }

    public function getNamedFunctions()
    {
        return $this->getValue('named functions');
    }

    public function getAnonymousFunctions()
    {
        return $this->getValue('anonymous functions');
    }

    public function toArray()
    {
        return [
            'files' => $this->getFiles(),
            'loc' => $this->getLines(),
            'lloc' => $this->getLogicalLines(),
            'llocClasses' => $this->getClassLines(),
            'llocFunctions' => $this->getFunctionLines(),
            'llocGlobal' => $this->getNotInClassesOrFunctions(),
            'cloc' => $this->getCommentLines(),
            'ccn' => $this->getComplexity(),
            'ccnMethods' => $this->getMethodComplexity(),
            'functions' => $this->getFunctions(),
            'namedFunctions' => $this->getNamedFunctions(),
            'anonymousFunctions' => $this->getAnonymousFunctions(),
            'ccnByLloc' => $this->getAverageComplexityPerLogicalLine(),
            'llocByNof' => $this->getAverageFunctionLength(),
            'directories' => $this->getDirectories(),
            'classCcnMin' => $this->getMinimumClassComplexity(),
            'classCcnAvg' => $this->getAverageComplexityPerClass(),
            'classCcnMax' => $this->getMaximumClassComplexity(),
            'classLlocMin' => $this->getMinimumClassLength(),
            'classLlocAvg' => $this->getAverageClassLength(),
            'classLlocMax' => $this->getMaximumClassLength(),
            'methodCcnMin' => $this->getMinimumMethodComplexity(),
            'methodCcnAvg' => $this->getAverageComplexityPerMethod(),
            'methodCcnMax' => $this->getMaximumMethodComplexity(),
            'methodLlocMin' => $this->getMinimumMethodLength(),
            'methodLlocAvg' => $this->getAverageMethodLength(),
            'methodLlocMax' => $this->getMaximumMethodLength(),
            'ncloc' => $this->getNonCommentLines(),
        ];
    }

    private function getAverage($key)
    {
        return $this->divide($this->getSum($key), $this->getCount($key));
    }

    private function getCount($key)
    {
        return isset($this->counts[$key]) ? count($this->counts[$key]) : 0;
    }

    private function getSum($key)
    {
        return isset($this->counts[$key]) ? array_sum($this->counts[$key]) : 0;
    }

    private function getMaximum($key)
    {
        return isset($this->counts[$key]) ? max($this->counts[$key]) : 0;
    }

    private function getMinimum($key)
    {
        return isset($this->counts[$key]) ? min($this->counts[$key]) : 0;
    }

    private function getValue($key)
    {
        return isset($this->counts[$key]) ? $this->counts[$key] : 0;
    }

    private function divide($x, $y)
    {
        return $y != 0 ? $x / $y : 0;
    }
}
