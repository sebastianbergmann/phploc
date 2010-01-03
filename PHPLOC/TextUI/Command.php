<?php
/**
 * phploc
 *
 * Copyright (c) 2009-2010, Sebastian Bergmann <sb@sebastian-bergmann.de>.
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
 * @copyright 2009-2010 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @since     File available since Release 1.0.0
 */

require_once 'File/Iterator/Factory.php';
require_once 'PHPLOC/Analyser.php';
require_once 'PHPLOC/TextUI/ResultPrinter/Text.php';
require_once 'PHPLOC/TextUI/ResultPrinter/XML.php';

require_once 'ezc/Base/base.php';

function __autoload($className)
{
    ezcBase::autoload($className);
}

/**
 * TextUI frontend for PHPLOC.
 *
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009-2010 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://github.com/sebastianbergmann/phploc/tree
 * @since     Class available since Release 1.0.0
 */
class PHPLOC_TextUI_Command
{
    /**
     * Main method.
     */
    public static function main()
    {
        $input  = new ezcConsoleInput;
        $output = new ezcConsoleOutput;

        $input->registerOption(
          new ezcConsoleOption(
            '',
            'count-tests',
            ezcConsoleInput::TYPE_NONE,
            FALSE,
            FALSE
           )
        );

        $input->registerOption(
          new ezcConsoleOption(
            '',
            'exclude',
            ezcConsoleInput::TYPE_STRING,
            array(),
            TRUE
           )
        );

        $input->registerOption(
          new ezcConsoleOption(
            'h',
            'help',
            ezcConsoleInput::TYPE_NONE,
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
          new ezcConsoleOption(
            '',
            'log-xml',
            ezcConsoleInput::TYPE_STRING,
            NULL,
            FALSE
           )
        );

        $input->registerOption(
          new ezcConsoleOption(
            '',
            'suffixes',
            ezcConsoleInput::TYPE_STRING,
            'php',
            FALSE
           )
        );

        $input->registerOption(
          new ezcConsoleOption(
            'v',
            'version',
            ezcConsoleInput::TYPE_NONE,
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
          new ezcConsoleOption(
            '',
            'verbose',
            ezcConsoleInput::TYPE_NONE
           )
        );

        try {
            $input->process();
        }

        catch (ezcConsoleOptionException $e) {
            $output->outputText($e->getMessage());
            exit(1);
        }

        if ($input->getOption('help')->value) {
            self::showHelp($output);
            exit(0);
        }

        else if ($input->getOption('version')->value) {
            self::printVersionString($output);
            exit(0);
        }

        $arguments  = $input->getArguments();
        $countTests = $input->getOption('count-tests')->value;
        $exclude    = $input->getOption('exclude')->value;
        $logXml     = $input->getOption('log-xml')->value;

        $suffixes = explode(',', $input->getOption('suffixes')->value);
        array_map('trim', $suffixes);

        if ($input->getOption('verbose')->value !== FALSE) {
            $verbose = $output;
        } else {
            $verbose = NULL;
        }

        if (!empty($arguments)) {
            $files = File_Iterator_Factory::getFilesAsArray(
              $arguments, $suffixes, array(), $exclude
            );
        } else {
            self::showHelp($output);
            exit(1);
        }

        self::printVersionString($output);

        $analyser = new PHPLOC_Analyser($verbose);
        $count    = $analyser->countFiles($files, $countTests);

        $printer = new PHPLOC_TextUI_ResultPrinter_Text;
        $output->outputText($printer->printResult($count, $countTests));

        if ($logXml) {
            $printer = new PHPLOC_TextUI_ResultPrinter_XML;
            $printer->printResult($logXml, $count);
        }
    }

    /**
     * Shows the help.
     *
     * @param ezcConsoleOutput $output
     */
    protected static function showHelp(ezcConsoleOutput $output)
    {
        self::printVersionString($output);

        $output->outputText(<<<EOT
Usage: phploc [switches] <directory|file>

  --count-tests            Count PHPUnit test case classes and test methods.

  --log-xml <file>         Write result in XML format to file.

  --exclude <directory>    Exclude <directory> from code analysis.
  --suffixes <suffix,...>  A comma-separated list of file suffixes to check.

  --help                   Prints this usage information.
  --version                Prints the version and exits.

  --verbose                Print progress bar.

EOT
);
    }

    /**
     * Prints the version string.
     *
     * @param ezcConsoleOutput $output
     */
    protected static function printVersionString(ezcConsoleOutput $output)
    {
        $output->outputText(
          "phploc @package_version@ by Sebastian Bergmann.\n\n"
        );
    }
}
?>
