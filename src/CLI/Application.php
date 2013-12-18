<?php
/**
 * phploc
 *
 * Copyright (c) 2009-2013, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package   phploc
 * @author    Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright 2009-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @since     File available since Release 2.0.0
 */

namespace SebastianBergmann\PHPLOC\CLI
{
    use SebastianBergmann\Version;
    use Symfony\Component\Console\Application as AbstractApplication;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\ArgvInput;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Input\ArrayInput;

    /**
     * TextUI frontend for PHPLOC.
     *
     * @author    Sebastian Bergmann <sebastian@phpunit.de>
     * @copyright 2009-2013 Sebastian Bergmann <sebastian@phpunit.de>
     * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
     * @link      http://github.com/sebastianbergmann/phploc/tree
     * @since     Class available since Release 2.0.0
     */
    class Application extends AbstractApplication
    {
        public function __construct()
        {
            $version = new Version('2.0.4', __DIR__);
            parent::__construct('phploc', $version->getVersion());
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
         * @return integer 0 if everything went fine, or an error code
         */
        public function doRun(InputInterface $input, OutputInterface $output)
        {
            if (!$input->hasParameterOption('--quiet')) {
                $output->write(
                  sprintf(
                    "phploc %s by Sebastian Bergmann.\n\n", $this->getVersion()
                  )
                );
            }

            if ($input->hasParameterOption('--version') ||
                $input->hasParameterOption('-V')) {
                exit;
            }

            if (!$input->getFirstArgument()) {
                $input = new ArrayInput(array('--help'));
            }

            parent::doRun($input, $output);
        }
    }
}
