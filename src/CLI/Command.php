<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\PHPLOC\CLI
{
    use SebastianBergmann\FinderFacade\FinderFacade;
    use SebastianBergmann\Git\Git;
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
     * @copyright Sebastian Bergmann <sebastian@phpunit.de>
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
                    $_count['commit'] = $revision['sha1'];
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
            try {
                $finder = new FinderFacade($arguments, $excludes, $names, $namesExclude);
                $files  = $finder->findFiles();
            } catch (\InvalidArgumentException $ex) {
                return FALSE;
            }

            if (empty($files)) {
                return FALSE;
            }

            $analyser = new Analyser;

            return $analyser->countFiles($files, $countTests);
        }

        /**
         * @param  InputInterface $input
         * @param  string         $option
         * @return string[]
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
