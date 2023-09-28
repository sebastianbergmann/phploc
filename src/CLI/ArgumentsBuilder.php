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

use function array_values;
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

        $directories = $options[1];
        $exclude     = [];
        $suffixes    = ['.php'];
        $debug       = false;
        $help        = false;
        $version     = false;

        foreach ($options[0] as $option) {
            switch ($option[0]) {
                case '--suffix':
                    $suffixes[] = $option[1];

                    break;

                case '--exclude':
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
            array_values($directories),
            $suffixes,
            $exclude,
            $debug,
            $help,
            $version,
        );
    }
}
