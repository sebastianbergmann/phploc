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

#[CoversClass(TextResultFormatter::class)]
#[UsesClass(Result::class)]
#[Small]
final class TextResultFormatterTest extends TestCase
{
    public function testFormatsResultAsText(): void
    {
        $this->assertStringEqualsFile(
            __DIR__ . '/../_expectations/result.txt',
            (new TextResultFormatter)->format(
                new Result([], 1, 2, 10, 4, 6, 3, 7, 8, 9, 10, 11, 12, 13, 14, 15),
            ),
        );
    }
}
