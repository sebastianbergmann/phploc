<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\PHPLOC\Log;

use SebastianBergmann\PHPLOC\Publisher;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * A ResultPrinter for the TextUI.
 *
 * @since     Class available since Release 1.0.0
 */
class Text
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * Prints a result set.
     *
     * @param OutputInterface $output
     * @param Publisher       $publisher
     * @param bool            $printTests
     */
    public function printResult(OutputInterface $output, Publisher $publisher, $printTests)
    {
        $this->output = $output;

        if ($publisher->getDirectories() > 0) {
            $this->addLineWithInt(0, 'Directories', $publisher->getDirectories());
            $this->addLineWithInt(0, 'Files', $publisher->getFiles());
            $this->addEmptyLine();
        }

        $this->addLine(0, 'Size');
        $this->addLineWithInt(1, 'Lines of Code (LOC)', $publisher->getLines());
        $this->addLineWithInt(1, 'Comment Lines of Code (CLOC)', $publisher->getCommentLines(), $publisher->getLines());
        $this->addLineWithInt(1, 'Non-Comment Lines of Code (NCLOC)', $publisher->getNonCommentLines(), $publisher->getLines());
        $this->addLineWithInt(1, 'Logical Lines of Code (LLOC)', $publisher->getLogicalLines(), $publisher->getLines());
        $this->addLineWithInt(2, 'Classes', $publisher->getClassLines(), $publisher->getLogicalLines());
        $this->addLineWithInt(3, 'Average Class Length', floor($publisher->getAverageClassLength()));
        $this->addLineWithInt(4, 'Minimum Class Length', $publisher->getMinimumClassLength());
        $this->addLineWithInt(4, 'Maximum Class Length', $publisher->getMaximumClassLength());
        $this->addLineWithInt(3, 'Average Method Length', floor($publisher->getAverageMethodLength()));
        $this->addLineWithInt(4, 'Minimum Method Length', $publisher->getMinimumMethodLength());
        $this->addLineWithInt(4, 'Maximum Method Length', $publisher->getMaximumMethodLength());
        $this->addLineWithInt(2, 'Functions', $publisher->getFunctionLines(), $publisher->getLogicalLines());
        $this->addLineWithInt(3, 'Average Function Length', floor($publisher->getAverageFunctionLength()));
        $this->addLineWithInt(2, 'Not in classes or functions', $publisher->getNotInClassesOrFunctions(), $publisher->getLogicalLines());
        $this->addEmptyLine();

        $this->addLine(0, 'Cyclomatic Complexity');
        $this->addLineWithFloat(1, 'Average Complexity per LLOC', $publisher->getAverageComplexityPerLogicalLine());
        $this->addLineWithFloat(1, 'Average Complexity per Class', $publisher->getAverageComplexityPerClass());
        $this->addLineWithFloat(2, 'Minimum Class Complexity', $publisher->getMinimumClassComplexity());
        $this->addLineWithFloat(2, 'Maximum Class Complexity', $publisher->getMaximumClassComplexity());
        $this->addLineWithFloat(1, 'Average Complexity per Method', $publisher->getAverageComplexityPerMethod());
        $this->addLineWithFloat(2, 'Minimum Method Complexity', $publisher->getMinimumMethodComplexity());
        $this->addLineWithFloat(2, 'Maximum Method Complexity', $publisher->getMaximumMethodComplexity());
        $this->addEmptyLine();

        $this->addLine(0, 'Dependencies');
        $this->addLineWithInt(1, 'Global Accesses', $publisher->getGlobalAccesses());
        $this->addLineWithInt(2, 'Global Constants', $publisher->getGlobalConstantAccesses(), $publisher->getGlobalAccesses());
        $this->addLineWithInt(2, 'Global Variables', $publisher->getGlobalVariableAccesses(), $publisher->getGlobalAccesses());
        $this->addLineWithInt(2, 'Super-Global Variables', $publisher->getSuperGlobalVariableAccesses(), $publisher->getGlobalAccesses());
        $this->addLineWithInt(1, 'Attribute Accesses', $publisher->getAttributeAccesses());
        $this->addLineWithInt(2, 'Non-Static', $publisher->getNonStaticAttributeAccesses(), $publisher->getAttributeAccesses());
        $this->addLineWithInt(2, 'Static', $publisher->getStaticAttributeAccesses(), $publisher->getAttributeAccesses());
        $this->addLineWithInt(1, 'Method Calls', $publisher->getMethodCalls());
        $this->addLineWithInt(2, 'Non-Static', $publisher->getNonStaticMethodCalls(), $publisher->getMethodCalls());
        $this->addLineWithInt(2, 'Static', $publisher->getStaticMethodCalls(), $publisher->getMethodCalls());
        $this->addEmptyLine();

        $this->addLine(0, 'Structure');
        $this->addLineWithInt(1, 'Namespaces', $publisher->getNamespaces());
        $this->addLineWithInt(1, 'Interfaces', $publisher->getInterfaces());
        $this->addLineWithInt(1, 'Traits', $publisher->getTraits());
        $this->addLineWithInt(1, 'Classes', $publisher->getClasses());
        $this->addLineWithInt(2, 'Abstract Classes', $publisher->getAbstractClasses(), $publisher->getClasses());
        $this->addLineWithInt(2, 'Concrete Classes', $publisher->getConcreteClasses(), $publisher->getClasses());
        $this->addLineWithInt(1, 'Methods', $publisher->getMethods());
        $this->addLine(2, 'Scope');
        $this->addLineWithInt(3, 'Non-Static Methods', $publisher->getNonStaticMethods(), $publisher->getMethods());
        $this->addLineWithInt(3, 'Static Methods', $publisher->getStaticMethods(), $publisher->getMethods());
        $this->addLine(2, 'Visibility');
        $this->addLineWithInt(3, 'Public Methods', $publisher->getPublicMethods(), $publisher->getMethods());
        $this->addLineWithInt(3, 'Non-Public Methods', $publisher->getNonPublicMethods(), $publisher->getMethods());
        $this->addLineWithInt(1, 'Functions', $publisher->getFunctions());
        $this->addLineWithInt(2, 'Named Functions', $publisher->getNamedFunctions(), $publisher->getFunctions());
        $this->addLineWithInt(2, 'Anonymous Functions', $publisher->getAnonymousFunctions(), $publisher->getFunctions());
        $this->addLineWithInt(1, 'Constants', $publisher->getConstants());
        $this->addLineWithInt(2, 'Global Constants', $publisher->getGlobalConstants(), $publisher->getConstants());
        $this->addLineWithInt(2, 'Class Constants', $publisher->getClassConstants(), $publisher->getConstants());

        if ($printTests) {
            $this->addEmptyLine();
            $this->addLine(0, 'Tests');
            $this->addLineWithInt(1, 'Classes', $publisher->getTestClasses());
            $this->addLineWithInt(1, 'Methods', $publisher->getTestMethods());
        }
    }

    private function addLineWithFloat($indent, $name, $value)
    {
        $this->addLine($indent, $name, str_pad(number_format($value, 2, '.', ''), 11, ' ', STR_PAD_LEFT));
    }

    private function addLineWithInt($indent, $name, $number, $total = null)
    {
        $value = str_pad(number_format($number, 0, '.', ''), 11, ' ', STR_PAD_LEFT);
        if (null !== $total) {
            $value .= ' ('.$this->getPercent($number, $total).'%)';
        }
        $this->addLine($indent, $name, $value);
    }

    private function addEmptyLine()
    {
        $this->addLine(0, '');
    }

    private function addLine($indent, $name, $value = null)
    {
        $line = str_repeat('  ', $indent).$name;

        if (null !== $value) {
            $line = str_pad($line, 42).' '.$value;
        }

        $this->output->writeln($line);
    }

    private function getPercent($dividend, $divisor)
    {
        if (0 === $dividend || 0 === $divisor) {
            return '0.00';
        }
        if ($dividend === $divisor) {
            return '100.00';
        }

        return  number_format(100 * $dividend / $divisor, 2);
    }
}