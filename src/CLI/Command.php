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
    use SebastianBergmann\FinderFacade\FinderFacade;
    use SebastianBergmann\Git;
    use SebastianBergmann\PHPLOC\Analyser;
    use SebastianBergmann\PHPLOC\Log\CSV\History;
    use SebastianBergmann\PHPLOC\Log\CSV\Single;
    use SebastianBergmann\PHPLOC\Log\Text;
    use SebastianBergmann\PHPLOC\Log\XML;
    use Symfony\Component\Console\Command\Command as AbstractCommand;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Output\OutputInterface;

    /**
     * @author    Sebastian Bergmann <sebastian@phpunit.de>
     * @copyright 2009-2013 Sebastian Bergmann <sebastian@phpunit.de>
     * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
     * @link      http://github.com/sebastianbergmann/phploc/tree
     * @since     Class available since Release 2.0.0
     */
    class Command extends AbstractCommand
    {
        /**
         * Configures the current command.
         */
        protected function configure()
        {
            $this->setName('phploc')
                 ->setDefinition(
                     array(
                       new InputArgument(
                         'values',
                         InputArgument::IS_ARRAY
                       )
                     )
                   )
                 ->addOption(
                     'names',
                     NULL,
                     InputOption::VALUE_REQUIRED,
                     'A comma-separated list of file names to check',
                     array('*.php')
                   )
                 ->addOption(
                     'names-exclude',
                     NULL,
                     InputOption::VALUE_REQUIRED,
                     'A comma-separated list of file names to exclude',
                    array()
                   )
                 ->addOption(
                     'count-tests',
                     NULL,
                     InputOption::VALUE_NONE,
                     'Count PHPUnit test case classes and test methods'
                   )
                 ->addOption(
                     'git-repository',
                     NULL,
                     InputOption::VALUE_REQUIRED,
                     'Collect metrics over the history of a Git repository'
                   )
                 ->addOption(
                     'exclude',
                     NULL,
                     InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                     'Exclude a directory from code analysis'
                   )
                 ->addOption(
                     'log-csv',
                     NULL,
                     InputOption::VALUE_REQUIRED,
                     'Write result in CSV format to file'
                   )
                 ->addOption(
                     'log-xml',
                     NULL,
                     InputOption::VALUE_REQUIRED,
                     'Write result in XML format to file'
                   )
                 ->addOption(
                     'progress',
                     null,
                     InputOption::VALUE_NONE,
                     'Show progress bar'
                   );
        }

        /**
         * Executes the current command.
         *
         * @param InputInterface  $input  An InputInterface instance
         * @param OutputInterface $output An OutputInterface instance
         *
         * @return null|integer null or 0 if everything went fine, or an error code
         */
        protected function execute(InputInterface $input, OutputInterface $output)
        {
            if (!$input->getOption('git-repository')) {
                return $this->executeSingle($input, $output);
            } else {
                return $this->executeHistory($input, $output);
            }
        }

        /**
         * @param InputInterface  $input  An InputInterface instance
         * @param OutputInterface $output An OutputInterface instance
         *
         * @return null|integer null or 0 if everything went fine, or an error code
         */
        private function executeSingle(InputInterface $input, OutputInterface $output)
        {
            $count = $this->count(
              $input->getArgument('values'),
              $input->getOption('exclude'),
              $this->handleCSVOption($input, 'names'),
              $this->handleCSVOption($input, 'names-exclude'),
              $input->getOption('count-tests')
            );

            if (!$count) {
                $output->writeln('No files found to scan');
                exit(1);
            }

            $printer = new Text;

            $printer->printResult(
              $output, $count, $input->getOption('count-tests')
            );

            if ($input->getOption('log-csv')) {
                $printer = new Single;
                $printer->printResult($input->getOption('log-csv'), $count);
            }

            if ($input->getOption('log-xml')) {
                $printer = new XML;
                $printer->printResult($input->getOption('log-xml'), $count);
            }
        }

        /**
         * @param InputInterface  $input  An InputInterface instance
         * @param OutputInterface $output An OutputInterface instance
         *
         * @return null|integer null or 0 if everything went fine, or an error code
         */
        private function executeHistory(InputInterface $input, OutputInterface $output)
        {
            $git            = new Git($input->getOption('git-repository'));
            $currentBranch  = $git->getCurrentBranch();
            $revisions      = $git->getRevisions();
            $count          = array();
            $progressHelper = NULL;

            if ($input->getOption('progress')) {
                $progressHelper = $this->getHelperSet()->get('progress');
                $progressHelper->start($output, count($revisions));
            }

            foreach ($revisions as $revision) {
                $git->checkout($revision['sha1']);

                $directories = array();

                foreach ($input->getArgument('values') as $value) {
                    $directory = realpath($value);

                    if ($directory) {
                        $directories[] = $directory;
                    }
                }

                $_count = $this->count(
                  $directories,
                  $input->getOption('exclude'),
                  $this->handleCSVOption($input, 'names'),
                  $this->handleCSVOption($input, 'names-exclude'),
                  $input->getOption('count-tests')
                );

                if ($_count) {
                    $count[$revision['date']->format(\DateTime::W3C)] = $_count;
                }

                if ($progressHelper !== NULL) {
                    $progressHelper->advance();
                }
            }

            $git->checkout($currentBranch);

            if ($progressHelper !== NULL) {
                $progressHelper->finish();
                $output->writeln('');
            }

            if ($input->getOption('log-csv')) {
                $printer = new History;
                $printer->printResult($input->getOption('log-csv'), $count);
            }
        }

        private function count(array $arguments, $excludes, $names, $namesExclude, $countTests)
        {
            $finder = new FinderFacade($arguments, $excludes, $names, $namesExclude);
            $files  = $finder->findFiles();

            if (empty($files)) {
                return FALSE;
            }

            $analyser = new Analyser;

            return $analyser->countFiles($files, $countTests);
        }

        /**
         * @param  Symfony\Component\Console\Input\InputOption $input
         * @param  string                                      $option
         * @return array
         */
        private function handleCSVOption(InputInterface $input, $option)
        {
            $result = $input->getOption($option);

            if (!is_array($result)) {
                $result = explode(',', $result);
                array_map('trim', $result);
            }

            return $result;
        }
    }
}
