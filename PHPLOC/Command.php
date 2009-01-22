<?php
/**
 * phploc
 *
 * Copyright (c) 2009, Sebastian Bergmann <sb@sebastian-bergmann.de>.
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
 * @copyright 2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @since     File available since Release 1.0.0
 */

require 'PHPLOC/Getopt.php';
require 'PHPLOC/FilterIterator.php';

/**
 *
 *
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      
 * @since     Class available since Release 1.0.0
 */
class PHPLOC_Command
{
    public static function main()
    {
        try {
            $options = PHPLOC_Getopt::getopt(
              $_SERVER['argv'],
              '',
              array(
                'help',
                'version'
              )
            );
        }

        catch (RuntimeException $e) {
            self::showError($e->getMessage());
        }

        foreach ($options[0] as $option) {
            switch ($option[0]) {
                case '--help': {
                    self::showHelp();
                    exit(0);
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
            if (is_dir($options[1][0])) {
                $files = new PHPLOC_FilterIterator(
                  new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($options[1][0])
                  )
                );
            }

            else if (is_file($options[1][0])) {
                $files = array(new SPLFileInfo($options[1][0]));
            }
        }

        if (isset($files)) {
            self::countFiles($files);
        } else {
            self::showHelp();
        }
    }

    protected static function countFiles($files)
    {
        $count = array(
          'files' => 0, 'loc' => 0, 'cloc' => 0, 'ncloc' => 0
        );

        foreach ($files as $file) {
            $_count = self::countFile($file->getPathName());

            $count['loc']   += $_count['loc'];
            $count['cloc']  += $_count['cloc'];
            $count['ncloc'] += $_count['ncloc'];
            $count['files']++;
        }

        self::printVersionString();

        printf(
          "Files: %d, LOC: %d, CLOC: %d, NCLOC: %d.\n",
          $count['files'],
          $count['loc'],
          $count['cloc'],
          $count['ncloc']
        );
    }

    /**
     * Counts LOC, CLOC, and NCLOC for a file.
     *
     * @param  string $file
     * @return array
     */
    protected static function countFile($file)
    {
        $loc  = count(file($file));
        $cloc = 0;

        foreach (token_get_all(file_get_contents($file)) as $token) {
            if (is_string($token)) {
                continue;
            }

            list ($token, $value) = $token;

            if ($token == T_COMMENT || $token == T_DOC_COMMENT) {
                $cloc += count(explode("\n", $value));
            }
        }

        return array(
          'loc' => $loc, 'cloc' => $cloc, 'ncloc' => $loc - $cloc
        );
    }

    /**
     * Shows an error.
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
Usage: phploc [switches] <directory>
       phploc [switches] <file>

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
