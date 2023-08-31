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

use const PHP_EOL;
use function dirname;
use function printf;
use SebastianBergmann\FileIterator\Facade;
use SebastianBergmann\Version;

final class Application
{
    private const VERSION = '8.0';

    /**
     * @psalm-param list<non-empty-string> $argv
     */
    public function run(array $argv): int
    {
        $this->printVersion();

        try {
            $arguments = (new ArgumentsBuilder)->build($argv);
        } catch (Exception $e) {
            print PHP_EOL . $e->getMessage() . PHP_EOL;

            return 1;
        }

        if ($arguments->version()) {
            return 0;
        }

        print PHP_EOL;

        if ($arguments->help()) {
            $this->help();

            return 0;
        }

        $files = (new Facade)->getFilesAsArray(
            $arguments->directories(),
            $arguments->suffixes(),
            '',
            $arguments->exclude(),
        );

        if (empty($files)) {
            print 'No files found to scan' . PHP_EOL;

            return 1;
        }

        $result = (new Analyser)->analyse($files);

        print (new TextResultFormatter)->format($result);

        return 0;
    }

    private function printVersion(): void
    {
        printf(
            'phploc %s by Sebastian Bergmann.' . PHP_EOL,
            (new Version(self::VERSION, dirname(__DIR__)))->asString(),
        );
    }

    private function help(): void
    {
        print <<<'EOT'
Usage:
  phploc [options] <directory>

Options for selecting files:

  --suffix <suffix> Include files with names ending in <suffix> in the analysis
                    (default: .php; can be given multiple times)
  --exclude <path>  Exclude files with <path> in their path from the analysis
                    (can be given multiple times)

EOT;
    }
}
