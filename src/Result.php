<?php declare(strict_types=1);
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\PHPLOC;

final readonly class Result
{
    /**
     * @psalm-var list<non-empty-string>
     */
    private array $errors;

    /**
     * @psalm-var non-negative-int
     */
    private int $directories;

    /**
     * @psalm-var non-negative-int
     */
    private int $files;

    /**
     * @psalm-var non-negative-int
     */
    private int $linesOfCode;

    /**
     * @psalm-var non-negative-int
     */
    private int $commentLinesOfCode;

    /**
     * @psalm-var non-negative-int
     */
    private int $nonCommentLinesOfCode;

    /**
     * @psalm-var non-negative-int
     */
    private int $logicalLinesOfCode;

    /**
     * @psalm-var non-negative-int
     */
    private int $functions;

    /**
     * @psalm-var non-negative-int
     */
    private int $lowestCyclomaticComplexityForFunction;
    private float $averageCyclomaticComplexityForFunction;

    /**
     * @psalm-var non-negative-int
     */
    private int $highestCyclomaticComplexityForFunction;

    /**
     * @psalm-var non-negative-int
     */
    private int $classesOrTraits;

    /**
     * @psalm-var non-negative-int
     */
    private int $methods;

    /**
     * @psalm-var non-negative-int
     */
    private int $lowestCyclomaticComplexityForMethod;
    private float $averageCyclomaticComplexityForMethod;

    /**
     * @psalm-var non-negative-int
     */
    private int $highestCyclomaticComplexityForMethod;

    /**
     * @psalm-param list<non-empty-string> $errors
     * @psalm-param non-negative-int $directories
     * @psalm-param non-negative-int $files
     * @psalm-param non-negative-int $linesOfCode
     * @psalm-param non-negative-int $commentLinesOfCode
     * @psalm-param non-negative-int $nonCommentLinesOfCode
     * @psalm-param non-negative-int $logicalLinesOfCode
     * @psalm-param non-negative-int $functions
     * @psalm-param non-negative-int $lowestCyclomaticComplexityForFunction
     * @psalm-param non-negative-int $highestCyclomaticComplexityForFunction
     * @psalm-param non-negative-int $classesOrTraits
     * @psalm-param non-negative-int $methods
     * @psalm-param non-negative-int $lowestCyclomaticComplexityForMethod
     * @psalm-param non-negative-int $highestCyclomaticComplexityForMethod
     */
    public function __construct(array $errors, int $directories, int $files, int $linesOfCode, int $commentLinesOfCode, int $nonCommentLinesOfCode, int $logicalLinesOfCode, int $functions, int $lowestCyclomaticComplexityForFunction, float $averageCyclomaticComplexityForFunction, int $highestCyclomaticComplexityForFunction, int $classesOrTraits, int $methods, int $lowestCyclomaticComplexityForMethod, float $averageCyclomaticComplexityForMethod, int $highestCyclomaticComplexityForMethod)
    {
        $this->errors                                 = $errors;
        $this->directories                            = $directories;
        $this->files                                  = $files;
        $this->linesOfCode                            = $linesOfCode;
        $this->commentLinesOfCode                     = $commentLinesOfCode;
        $this->nonCommentLinesOfCode                  = $nonCommentLinesOfCode;
        $this->logicalLinesOfCode                     = $logicalLinesOfCode;
        $this->functions                              = $functions;
        $this->lowestCyclomaticComplexityForFunction  = $lowestCyclomaticComplexityForFunction;
        $this->averageCyclomaticComplexityForFunction = $averageCyclomaticComplexityForFunction;
        $this->highestCyclomaticComplexityForFunction = $highestCyclomaticComplexityForFunction;
        $this->classesOrTraits                        = $classesOrTraits;
        $this->methods                                = $methods;
        $this->lowestCyclomaticComplexityForMethod    = $lowestCyclomaticComplexityForMethod;
        $this->averageCyclomaticComplexityForMethod   = $averageCyclomaticComplexityForMethod;
        $this->highestCyclomaticComplexityForMethod   = $highestCyclomaticComplexityForMethod;
    }

    /**
     * @psalm-assert-if-true !empty $this->errors
     */
    public function hasErrors(): bool
    {
        return $this->errors !== [];
    }

    /**
     * @psalm-return list<non-empty-string>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function directories(): int
    {
        return $this->directories;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function files(): int
    {
        return $this->files;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function linesOfCode(): int
    {
        return $this->linesOfCode;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function commentLinesOfCode(): int
    {
        return $this->commentLinesOfCode;
    }

    public function commentLinesOfCodePercentage(): float
    {
        if ($this->linesOfCode() === 0) {
            return 0.0;
        }

        return ($this->commentLinesOfCode() / $this->linesOfCode()) * 100;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function nonCommentLinesOfCode(): int
    {
        return $this->nonCommentLinesOfCode;
    }

    public function nonCommentLinesOfCodePercentage(): float
    {
        if ($this->linesOfCode() === 0) {
            return 0.0;
        }

        return ($this->nonCommentLinesOfCode() / $this->linesOfCode()) * 100;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function logicalLinesOfCode(): int
    {
        return $this->logicalLinesOfCode;
    }

    public function logicalLinesOfCodePercentage(): float
    {
        if ($this->linesOfCode() === 0) {
            return 0.0;
        }

        return ($this->logicalLinesOfCode() / $this->linesOfCode()) * 100;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function functions(): int
    {
        return $this->functions;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function lowestCyclomaticComplexityForFunction(): int
    {
        return $this->lowestCyclomaticComplexityForFunction;
    }

    public function averageCyclomaticComplexityForFunction(): float
    {
        return $this->averageCyclomaticComplexityForFunction;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function highestCyclomaticComplexityForFunction(): int
    {
        return $this->highestCyclomaticComplexityForFunction;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function methods(): int
    {
        return $this->methods;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function lowestCyclomaticComplexityForMethod(): int
    {
        return $this->lowestCyclomaticComplexityForMethod;
    }

    public function averageCyclomaticComplexityForMethod(): float
    {
        return $this->averageCyclomaticComplexityForMethod;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function highestCyclomaticComplexityForMethod(): int
    {
        return $this->highestCyclomaticComplexityForMethod;
    }

    /**
     * @psalm-return non-negative-int
     */
    public function classesOrTraits(): int
    {
        return $this->classesOrTraits;
    }
}
