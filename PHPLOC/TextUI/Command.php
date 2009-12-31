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
require_once 'PHPLOC/TextUI/Getopt.php';
require_once 'PHPLOC/TextUI/ResultPrinter/Text.php';
require_once 'PHPLOC/TextUI/ResultPrinter/XML.php';

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
        try {
            $options = PHPLOC_TextUI_Getopt::getopt(
              $_SERVER['argv'],
              '',
              array(
                'count-tests',
                'exclude=',
                'help',
                'log-xml=',
                'suffixes=',
                'version'
              )
            );
        }

        catch (RuntimeException $e) {
            self::showError($e->getMessage());
        }

        $countTests = FALSE;
        $exclude    = array();
        $suffixes   = array('php');

        foreach ($options[0] as $option) {
            switch ($option[0]) {
                case '--count-tests': {
                    $countTests = TRUE;
                }
                break;

                case '--exclude': {
                    $exclude[] = $option[1];
                }
                break;

                case '--help': {
                    self::showHelp();
                    exit(0);
                }
                break;

                case '--log-xml': {
                    $logXml = $option[1];
                }
                break;

                case '--suffixes': {
                    $suffixes = explode(',', $option[1]);
                    array_map('trim', $suffixes);
                }
                break;

                case '--version': {
                    self::printVersionString();
                    exit(0);
                }
                break;
            }
        }

        if (isset($options[1][0])) {
            $files = File_Iterator_Factory::getFilesAsArray(
              $options[1], $suffixes, array(), $exclude
            );
        } else {
            self::showHelp();
            exit(1);
        }

        self::printVersionString();

        $analyser = new PHPLOC_Analyser;
        $count    = $analyser->countFiles($files, $countTests);
        $printer  = new PHPLOC_TextUI_ResultPrinter_Text;
        $printer->printResult($count, $countTests);

        if (isset($logXml)) {
            $printer = new PHPLOC_TextUI_ResultPrinter_XML;
            $printer->printResult($logXml, $count);
        }
    }

    /**
     * Shows an error.
     *
     * @param string $message
     */
    protected static function showError($message)
    {
        self::printVersionString();

        print $message;

        exit(1);
    }

    /**
     * Shows the help.
     */
    protected static function showHelp()
    {
        self::printVersionString();

        print <<<EOT
Usage: phploc [switches] <directory|file>

  --count-tests            Count PHPUnit test case classes and test methods.

  --log-xml <file>         Write result in XML format to file.

  --exclude <directory>    Exclude <directory> from code analysis.
  --suffixes <suffix,...>  A comma-separated list of file suffixes to check.

  --help                   Prints this usage information.
  --version                Prints the version and exits.

EOT;
    }

    /**
     * Prints the version string.
     */
    protected static function printVersionString()
    {
        print "phploc @package_version@ by Sebastian Bergmann.\n\n";
    }
}
?>
