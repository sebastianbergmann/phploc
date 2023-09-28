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
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Analyser::class)]
#[UsesClass(Result::class)]
#[Small]
final class AnalyserTest extends TestCase
{
    public function testAnalysesFiles(): void
    {
        $result = (new Analyser)->analyse(
            [
                __DIR__ . '/../_fixture/example_function.php',
                __DIR__ . '/../_fixture/ExampleClass.php',
                __DIR__ . '/../_fixture/ExampleInterface.php',
                __DIR__ . '/../_fixture/ExampleTrait.php',
            ],
            false,
        );

        $this->assertFalse($result->hasErrors());
        $this->assertSame(1, $result->directories());
        $this->assertSame(4, $result->files());
        $this->assertSame(152, $result->linesOfCode());
        $this->assertSame(32, $result->commentLinesOfCode());
        $this->assertSame(120, $result->nonCommentLinesOfCode());
        $this->assertSame(40, $result->logicalLinesOfCode());
        $this->assertSame(1, $result->functions());
        $this->assertSame(2, $result->classesOrTraits());
        $this->assertSame(2, $result->methods());
    }
}
