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
use function is_array;
use function is_string;
use SebastianBergmann\CliParser\Exception as CliParserException;
use SebastianBergmann\CliParser\Parser as CliParser;

final class ArgumentsBuilder
{
    /**
     * @psalm-param list<non-empty-string> $argv
     *
     * @throws ArgumentsBuilderException
     */
    public function build(array $argv): Arguments
    {
        try {
            $options = (new CliParser)->parse(
                $argv,
                'hv',
                [
                    'suffix=',
                    'exclude=',
                    'debug',
                    'help',
                    'version',
                ],
            );
        } catch (CliParserException $e) {
            throw new ArgumentsBuilderException(
                $e->getMessage(),
                $e->getCode(),
                $e,
            );
        }

        $directories = [];
        $exclude     = [];
        $suffixes    = ['.php'];
        $debug       = false;
        $help        = false;
        $version     = false;

        foreach ($options[1] as $directory) {
            assert(is_string($directory) && !empty($directory));

            $directories[] = $directory;
        }

        foreach ($options[0] as $option) {
            assert(is_array($option));

            switch ($option[0]) {
                case '--suffix':
                    assert(is_string($option[1]) && !empty($option[1]));

                    $suffixes[] = $option[1];

                    break;

                case '--exclude':
                    assert(is_string($option[1]) && !empty($option[1]));

                    $exclude[] = $option[1];

                    break;

                case '--debug':
                    $debug = true;

                    break;

                case 'h':
                case '--help':
                    $help = true;

                    break;

                case 'v':
                case '--version':
                    $version = true;

                    break;
            }
        }

        if (empty($directories) && !$help && !$version) {
            throw new ArgumentsBuilderException(
                'No directory specified',
            );
        }

        return new Arguments(
            $directories,
            $suffixes,
            $exclude,
            $debug,
            $help,
            $version,
        );
    }
}
