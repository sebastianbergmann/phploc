<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\PHPLOC\CLI;

use SebastianBergmann\FinderFacade\FinderFacade;
use SebastianBergmann\Git\Git;
use SebastianBergmann\Git\RuntimeException;
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
use Symfony\Component\Console\Helper\ProgressBar;

/**
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
                 [
                   new InputArgument(
                       'values',
                       InputArgument::IS_ARRAY
                   )
                 ]
             )
             ->addOption(
                 'names',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'A comma-separated list of file names to check',
                 ['*.php']
             )
             ->addOption(
                 'names-exclude',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'A comma-separated list of file names to exclude',
                 []
             )
             ->addOption(
                 'count-tests',
                 null,
                 InputOption::VALUE_NONE,
                 'Count PHPUnit test case classes and test methods'
             )
             ->addOption(
                 'git-repository',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'Collect metrics over the history of a Git repository'
             )
             ->addOption(
                 'exclude',
                 null,
                 InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                 'Exclude a directory from code analysis'
             )
             ->addOption(
                 'log-csv',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'Write result in CSV format to file'
             )
             ->addOption(
                 'log-xml',
                 null,
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
     * @return null|int null or 0 if everything went fine, or an error code
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
     * @return null|int null or 0 if everything went fine, or an error code
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
            $output,
            $count,
            $input->getOption('count-tests')
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
     * @return null|int null or 0 if everything went fine, or an error code
     */
    private function executeHistory(InputInterface $input, OutputInterface $output)
    {
        if (!is_dir($input->getOption('git-repository'))) {
            throw new RuntimeException(
                sprintf(
                    'Working directory "%s" does not exist',
                    $input->getOption('git-repository')
                )
            );
        }

        $git = new Git($input->getOption('git-repository'));

        if (!$git->isWorkingCopyClean()) {
            throw new RuntimeException(
                sprintf(
                    'Working directory "%s" is not clean',
                    $input->getOption('git-repository')
                )
            );
        }

        $currentBranch  = $git->getCurrentBranch();
        $revisions      = $git->getRevisions();
        $printer        = null;
        $progressBar    = null;

        if ($input->getOption('log-csv')) {
            $printer = new History($input->getOption('log-csv'));
        }

        if ($input->getOption('progress')) {
            $progressBar = new ProgressBar($output, count($revisions));
            $progressBar->start();
        }

        foreach ($revisions as $revision) {
            $git->checkout($revision['sha1']);

            $directories = [];

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
                $_count['date']   = $revision['date']->format(\DateTime::W3C);
                if ($printer) {
                    $printer->printRow($_count);
                }
            }

            if ($progressBar !== null) {
                $progressBar->advance();
            }
        }

        $git->checkout($currentBranch);

        if ($progressBar !== null) {
            $progressBar->finish();
            $output->writeln('');
        }

    }

    private function count(array $arguments, $excludes, $names, $namesExclude, $countTests)
    {
        try {
            $finder = new FinderFacade($arguments, $excludes, $names, $namesExclude);
            $files  = $finder->findFiles();
        } catch (\InvalidArgumentException $ex) {
            return false;
        }

        if (empty($files)) {
            return false;
        }

        $analyser = new Analyser;

        return $analyser->countFiles($files, $countTests);
    }

    /**
     * @param  InputInterface $input
     * @param  string         $option
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
