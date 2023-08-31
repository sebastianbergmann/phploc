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

use function assert;
use function number_format;
use function sprintf;

final readonly class TextResultFormatter
{
    /**
     * @psalm-return non-empty-string
     */
    public function format(Result $result): string
    {
        $buffer = sprintf(
            <<<'EOT'
Directories:                       %20s
Files:                             %20s

Lines of Code (LOC):               %20s
Comment Lines of Code (CLOC):      %20s (%.2f%%)
Non-Comment Lines of Code (NCLOC): %20s (%.2f%%)
Logical Lines of Code (LLOC):      %20s (%.2f%%)

EOT,
            number_format($result->directories()),
            number_format($result->files()),
            number_format($result->linesOfCode()),
            number_format($result->commentLinesOfCode()),
            $result->commentLinesOfCodePercentage(),
            number_format($result->nonCommentLinesOfCode()),
            $result->nonCommentLinesOfCodePercentage(),
            number_format($result->logicalLinesOfCode()),
            $result->logicalLinesOfCodePercentage(),
        );

        if ($result->classesOrTraits() > 0) {
            $buffer .= sprintf(
                <<<'EOT'

Classes or Traits                  %20s
  Methods                          %20s
    Cyclomatic Complexity
      Lowest                       %20.2f
      Average                      %20.2f
      Highest                      %20.2f

EOT,
                number_format($result->classesOrTraits()),
                number_format($result->methods()),
                number_format($result->lowestCyclomaticComplexityForMethod()),
                number_format($result->averageCyclomaticComplexityForMethod()),
                number_format($result->highestCyclomaticComplexityForMethod()),
            );
        }

        if ($result->functions() > 0) {
            $buffer .= sprintf(
                <<<'EOT'

Functions                          %20s
  Cyclomatic Complexity
    Lowest                         %20.2f
    Average                        %20.2f
    Highest                        %20.2f

EOT,
                number_format($result->functions()),
                number_format($result->lowestCyclomaticComplexityForFunction()),
                number_format($result->averageCyclomaticComplexityForFunction()),
                number_format($result->highestCyclomaticComplexityForFunction()),
            );
        }

        assert($buffer !== '');

        return $buffer;
    }
}
