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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

#[CoversClass(Result::class)]
#[Small]
final class ResultTest extends TestCase
{
    public function testMayHaveNoErrors(): void
    {
        $result = new Result([], 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertFalse($result->hasErrors());
        $this->assertSame([], $result->errors());
    }

    public function testMayHaveErrors(): void
    {
        $result = new Result(['error'], 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertTrue($result->hasErrors());
        $this->assertSame(['error'], $result->errors());
    }

    public function testHasDirectories(): void
    {
        $result = new Result([], 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(1, $result->directories());
    }

    public function testHasFiles(): void
    {
        $result = new Result([], 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(2, $result->files());
    }

    public function testHasLinesOfCode(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(10, $result->linesOfCode());
    }

    public function testHasCommentLinesOfCode(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(4, $result->commentLinesOfCode());
    }

    public function testHasCommentLinesOfCodePercentage(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(40.0, $result->commentLinesOfCodePercentage());

        $result = new Result([], 1, 2, 0, 0, 0, 0, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(0.0, $result->commentLinesOfCodePercentage());
    }

    public function testHasNonCommentLinesOfCode(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(6, $result->nonCommentLinesOfCode());
    }

    public function testHasNonCommentLinesOfCodePercentage(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(60.0, $result->nonCommentLinesOfCodePercentage());

        $result = new Result([], 1, 2, 0, 0, 0, 0, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(0.0, $result->nonCommentLinesOfCodePercentage());
    }

    public function testHasLogicalLinesOfCode(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(3, $result->logicalLinesOfCode());
    }

    public function testHasLogicalLinesOfCodePercentage(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(30.0, $result->logicalLinesOfCodePercentage());

        $result = new Result([], 1, 2, 0, 0, 0, 0, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(0.0, $result->logicalLinesOfCodePercentage());
    }

    public function testHasFunctions(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(7, $result->functions());
    }

    public function testHasLowestCyclomaticComplexityForFunction(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(8, $result->lowestCyclomaticComplexityForFunction());
    }

    public function testHasAverageCyclomaticComplexityForFunction(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(9.0, $result->averageCyclomaticComplexityForFunction());
    }

    public function testHasHighestCyclomaticComplexityForFunction(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(10, $result->highestCyclomaticComplexityForFunction());
    }

    public function testHasClassesOrTraits(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(11, $result->classesOrTraits());
    }

    public function testHasMethods(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(12, $result->methods());
    }

    public function testHasLowestCyclomaticComplexityForMethod(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(13, $result->lowestCyclomaticComplexityForMethod());
    }

    public function testHasAverageCyclomaticComplexityForMethod(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(14.0, $result->averageCyclomaticComplexityForMethod());
    }

    public function testHasHighestCyclomaticComplexityForMethod(): void
    {
        $result = new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        $this->assertSame(15, $result->highestCyclomaticComplexityForMethod());
    }
}
