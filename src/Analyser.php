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

use function array_sum;
use function array_unique;
use function assert;
use function count;
use function dirname;
use function explode;
use function file_get_contents;
use function is_string;
use function max;
use function min;
use function sprintf;
use function substr_count;
use PhpParser\Error;
use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use SebastianBergmann\Complexity\ComplexityCalculatingVisitor;
use SebastianBergmann\Complexity\ComplexityCollection;
use SebastianBergmann\LinesOfCode\LineCountingVisitor;
use SebastianBergmann\LinesOfCode\LinesOfCode;

final class Analyser
{
    /**
     * @psalm-param list<non-empty-string> $files
     */
    public function analyse(array $files): Result
    {
        $errors      = [];
        $directories = [];
        $complexity  = ComplexityCollection::fromList();

        /** @psalm-suppress MissingThrowsDocblock */
        $linesOfCode = new LinesOfCode(0, 0, 0, 0);

        foreach ($files as $file) {
            $directories[] = dirname($file);

            try {
                $result = $this->analyseFile($file);

                $complexity  = $complexity->mergeWith($result['complexity']);
                $linesOfCode = $linesOfCode->plus($result['linesOfCode']);
            } catch (ParserException $e) {
                $message = $e->getMessage();

                assert(is_string($message) && !empty($message));

                $errors[] = $message;
            }
        }

        $classesOrTraits = [];

        foreach ($complexity->isMethod() as $item) {
            $classesOrTraits[] = explode('::', $item->name())[0];
        }

        $classesOrTraits     = count(array_unique($classesOrTraits));
        $complexityFunctions = $complexity->isFunction();
        $numberOfFunctions   = $complexityFunctions->count();
        $complexityFunctions = $this->cyclomaticComplexityStatistics($complexityFunctions);
        $complexityMethods   = $complexity->isMethod();
        $numberOfMethods     = $complexityMethods->count();
        $complexityMethods   = $this->cyclomaticComplexityStatistics($complexityMethods);

        return new Result(
            $errors,
            count(array_unique($directories)),
            count($files),
            $linesOfCode->linesOfCode(),
            $linesOfCode->commentLinesOfCode(),
            $linesOfCode->nonCommentLinesOfCode(),
            $linesOfCode->logicalLinesOfCode(),
            $numberOfFunctions,
            $complexityFunctions['minimum'],
            $complexityFunctions['average'],
            $complexityFunctions['maximum'],
            $classesOrTraits,
            $numberOfMethods,
            $complexityMethods['minimum'],
            $complexityMethods['average'],
            $complexityMethods['maximum'],
        );
    }

    /**
     * @psalm-param non-empty-string $file
     *
     * @psalm-return array{complexity: ComplexityCollection, linesOfCode: LinesOfCode}
     *
     * @throws ParserException
     */
    private function analyseFile(string $file): array
    {
        $parser = $this->parser();
        $source = file_get_contents($file);
        $lines  = substr_count($source, "\n");

        if ($lines === 0 && !empty($source)) {
            $lines = 1;
        }

        assert($lines >= 0);

        try {
            $nodes = $parser->parse(file_get_contents($file));

            assert($nodes !== null);

            $traverser = new NodeTraverser;

            $complexityCalculatingVisitor = new ComplexityCalculatingVisitor(false);
            $lineCountingVisitor          = new LineCountingVisitor($lines);

            $traverser->addVisitor(new NameResolver);
            $traverser->addVisitor(new ParentConnectingVisitor);
            $traverser->addVisitor($complexityCalculatingVisitor);
            $traverser->addVisitor($lineCountingVisitor);

            $traverser->traverse($nodes);
        } catch (Error $error) {
            throw new ParserException(
                sprintf(
                    'Cannot parse %s: %s',
                    $file,
                    $error->getMessage(),
                ),
                $error->getCode(),
                $error,
            );
        }

        return [
            'complexity'  => $complexityCalculatingVisitor->result(),
            'linesOfCode' => $lineCountingVisitor->result(),
        ];
    }

    private function parser(): Parser
    {
        return (new ParserFactory)->create(
            ParserFactory::PREFER_PHP7,
            new Lexer,
        );
    }

    /**
     * @psalm-return array{minimum: non-negative-int, maximum: non-negative-int, average: float}
     */
    private function cyclomaticComplexityStatistics(ComplexityCollection $items): array
    {
        $values = [];

        foreach ($items as $item) {
            $values[] = $item->cyclomaticComplexity();
        }

        return [
            'minimum' => !empty($values) ? min($values) : 0,
            'maximum' => !empty($values) ? max($values) : 0,
            'average' => !empty($values) ? array_sum($values) / count($values) : 0,
        ];
    }
}
