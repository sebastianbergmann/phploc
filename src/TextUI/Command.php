<?php
/**
 * phploc
 *
 * Copyright (c) 2009-2012, Sebastian Bergmann <sb@sebastian-bergmann.de>.
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
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @since     File available since Release 1.0.0
 */

namespace SebastianBergmann\PHPLOC\TextUI
{
    use SebastianBergmann\FinderFacade\FinderFacade;
    use SebastianBergmann\PHPLOC\Analyser;
    use SebastianBergmann\PHPLOC\Version;
    use SebastianBergmann\PHPLOC\Log\CSV;
    use SebastianBergmann\PHPLOC\Log\TXT;
    use SebastianBergmann\PHPLOC\Log\XML;

    /**
     * TextUI frontend for PHPLOC.
     *
     * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
     * @copyright 2009-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
     * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
     * @link      http://github.com/sebastianbergmann/phploc/tree
     * @since     Class available since Release 1.0.0
     */
    class Command
    {
        /**
         * Main method.
         */
        public function main()
        {
            $input  = new \ezcConsoleInput;
            $output = new \ezcConsoleOutput;

            $input->registerOption(
              new \ezcConsoleOption(
                '',
                'count-tests',
                \ezcConsoleInput::TYPE_NONE,
                FALSE,
                FALSE
               )
            );

            $input->registerOption(
              new \ezcConsoleOption(
                '',
                'exclude',
                \ezcConsoleInput::TYPE_STRING,
                array(),
                TRUE
               )
            );

            $input->registerOption(
              new \ezcConsoleOption(
                'h',
                'help',
                \ezcConsoleInput::TYPE_NONE,
                NULL,
                FALSE,
                '',
                '',
                array(),
                array(),
                FALSE,
                FALSE,
                TRUE
               )
            );
			
			$input->registerOption(
              new \ezcConsoleOption(
                '',
                'log-txt',
                \ezcConsoleInput::TYPE_STRING
               )
            );

            $input->registerOption(
              new \ezcConsoleOption(
                '',
                'log-xml',
                \ezcConsoleInput::TYPE_STRING
               )
            );

            $input->registerOption(
              new \ezcConsoleOption(
                '',
                'log-csv',
                \ezcConsoleInput::TYPE_STRING
               )
            );

            $input->registerOption(
              new \ezcConsoleOption(
                '',
                'names',
                \ezcConsoleInput::TYPE_STRING,
                '*.php',
                FALSE
               )
            );

            $input->registerOption(
              new \ezcConsoleOption(
                'v',
                'version',
                \ezcConsoleInput::TYPE_NONE,
                NULL,
                FALSE,
                '',
                '',
                array(),
                array(),
                FALSE,
                FALSE,
                TRUE
               )
            );

            $input->registerOption(
              new \ezcConsoleOption(
                '',
                'progress',
                \ezcConsoleInput::TYPE_NONE
               )
            );

            try {
                $input->process();
            }

            catch (\ezcConsoleOptionException $e) {
                print $e->getMessage() . "\n";
                exit(1);
            }

            if ($input->getOption('help')->value) {
                $this->showHelp();
                exit(0);
            }

            else if ($input->getOption('version')->value) {
                $this->printVersionString();
                exit(0);
            }

            $arguments = $input->getArguments();

            if (empty($arguments)) {
                $this->showHelp();
                exit(1);
            }

            $countTests = $input->getOption('count-tests')->value;
            $excludes   = $input->getOption('exclude')->value;
            $logXml     = $input->getOption('log-xml')->value;
            $logCsv     = $input->getOption('log-csv')->value;
            $logTxt    = $input->getOption('log-txt')->value;
            $names      = explode(',', $input->getOption('names')->value);

            array_map('trim', $names);

            if ($input->getOption('progress')->value !== FALSE) {
                $progress = $output;
            } else {
                $progress = NULL;
            }

            $this->printVersionString();

            $finder = new FinderFacade($arguments, $excludes, $names);
            $files  = $finder->findFiles();

            if (empty($files)) {
                $this->showError("No files found to scan.\n");
            }

            $analyser = new Analyser($progress);
            $count    = $analyser->countFiles($files, $countTests);

            $printer = new ResultPrinter;
            $printer->printResult($count, $countTests);

            if ($logCsv) {
                $printer = new CSV;
                $printer->printResult($logCsv, $count);
            }
			
            if ($logTxt) {
                $printer = new TXT;
                $printer->printResult($logTxt, $count, $countTests);
            }

            if ($logXml) {
                $printer = new XML;
                $printer->printResult($logXml, $count);
            }
        }

        /**
         * Shows an error.
         *
         * @param string $message
         */
        protected function showError($message)
        {
            $this->printVersionString();

            print $message;

            exit(1);
        }

        /**
         * Shows the help.
         */
        protected function showHelp()
        {
            $this->printVersionString();

            print <<<EOT
Usage: phploc [switches] <directory|file> ...

  --count-tests            Count PHPUnit test case classes and test methods.

  --log-xml <file>         Write result in XML format to file.
  --log-csv <file>         Write result in CSV format to file.
  --log-txt <file>        Write result in TXT format to file.

  --exclude <dir>          Exclude <dir> from code analysis.
  --names <names>          A comma-separated list of file names to check.
                           (default: *.php)

  --help                   Prints this usage information.
  --version                Prints the version and exits.

  --progress               Print progress bar.

EOT
;
        }

        /**
         * Prints the version string.
         */
        protected function printVersionString()
        {
            printf(
              "phploc %s by Sebastian Bergmann.\n\n", Version::id()
            );
        }
    }
}
