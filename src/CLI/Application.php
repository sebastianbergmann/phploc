<?php declare(strict_types=1);
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\PHPLOC\CLI;

use SebastianBergmann\Version;
use Symfony\Component\Console\Application as AbstractApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * TextUI frontend for PHPLOC.
 */
class Application extends AbstractApplication
{
    public function __construct()
    {
        $version = new Version('6.0.2', \dirname(__DIR__, 2));

        parent::__construct('phploc', $version->getVersion());
    }

    /**
     * Overridden so that the application doesn't expect the command
     * name to be the first argument.
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();

        return $inputDefinition;
    }

    /**
     * Runs the current application.
     *
     * @param InputInterface  $input  An Input instance
     * @param OutputInterface $output An Output instance
     *
     * @return int 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->disableXdebug();

        if (!$input->hasParameterOption('--quiet')) {
            $output->write(
                \sprintf(
                    "phploc %s by Sebastian Bergmann.\n\n",
                    $this->getVersion()
                )
            );
        }

        if ($input->hasParameterOption('--version') ||
            $input->hasParameterOption('-V')) {
            exit;
        }

        if (!$input->getFirstArgument()) {
            $input = new ArrayInput(['--help']);
        }

        parent::doRun($input, $output);
    }

    /**
     * Gets the name of the command based on input.
     *
     * @param InputInterface $input The input interface
     *
     * @return string The command name
     */
    protected function getCommandName(InputInterface $input)
    {
        return 'phploc';
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new Command;

        return $defaultCommands;
    }

    private function disableXdebug(): void
    {
        if (!\extension_loaded('xdebug')) {
            return;
        }

        \ini_set('xdebug.scream', '0');
        \ini_set('xdebug.max_nesting_level', '8192');
        \ini_set('xdebug.show_exception_trace', '0');
        \ini_set('xdebug.show_error_trace', '0');

        \xdebug_disable();
    }
}
