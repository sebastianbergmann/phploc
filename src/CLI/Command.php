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
use SebastianBergmann\PHPLOC\Analyser;
use SebastianBergmann\PHPLOC\Log\Csv;
use SebastianBergmann\PHPLOC\Log\Json;
use SebastianBergmann\PHPLOC\Log\Text;
use SebastianBergmann\PHPLOC\Log\Xml;
use Symfony\Component\Console\Command\Command as AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends AbstractCommand
{
    /**
     * Configures the current command.
     */
    protected function configure(): void
    {
        $this->setName('phploc')
             ->setDefinition(
                 [
                     new InputArgument(
                         'values',
                         InputArgument::IS_ARRAY
                     ),
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
                 'log-json',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'Write result in JSON format to file'
             )
             ->addOption(
                 'log-xml',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'Write result in XML format to file'
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
        $count = $this->count(
            $input->getArgument('values'),
            $input->getOption('exclude'),
            $this->handleCSVOption($input, 'names'),
            $this->handleCSVOption($input, 'names-exclude'),
            $input->getOption('count-tests')
        );

        if (!$count) {
            $output->writeln('No files found to scan');
            exit(0);
        }

        $printer = new Text;

        $printer->printResult(
            $output,
            $count,
            $input->getOption('count-tests')
        );

        if ($input->getOption('log-csv')) {
            $printer = new Csv;
            $printer->printResult($input->getOption('log-csv'), $count);
        }

        if ($input->getOption('log-json')) {
            $printer = new Json;
            $printer->printResult($input->getOption('log-json'), $count);
        }

        if ($input->getOption('log-xml')) {
            $printer = new Xml;
            $printer->printResult($input->getOption('log-xml'), $count);
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
     * @param string $option
     *
     * @return array
     */
    private function handleCSVOption(InputInterface $input, $option)
    {
        $result = $input->getOption($option);

        return \is_array($result) ? $result : \explode(',', $result);
    }
}
