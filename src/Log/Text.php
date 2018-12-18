<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\PHPLOC\Log;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * A ResultPrinter for the TextUI.
 */
class Text
{
    /**
     * Prints a result set.
     *
     * @param bool $printTests
     */
    public function printResult(OutputInterface $output, array $count, $printTests): void
    {
        if ($count['directories'] > 0) {
            $output->write(
                \sprintf(
                    "Directories                                 %10d\n" .
                    "Files                                       %10d\n\n",
                    $count['directories'],
                    $count['files']
                )
            );
        }

        $format = <<<END
Size
  Lines of Code (LOC)                       %10d
  Comment Lines of Code (CLOC)              %10d (%.2f%%)
  Non-Comment Lines of Code (NCLOC)         %10d (%.2f%%)
  Logical Lines of Code (LLOC)              %10d (%.2f%%)
    Classes                                 %10d (%.2f%%)
      Average Class Length                  %10d
        Minimum Class Length                %10d
        Maximum Class Length                %10d
      Average Method Length                 %10d
        Minimum Method Length               %10d
        Maximum Method Length               %10d
    Functions                               %10d (%.2f%%)
      Average Function Length               %10d
    Not in classes or functions             %10d (%.2f%%)

Cyclomatic Complexity
  Average Complexity per LLOC               %10.2f
  Average Complexity per Class              %10.2f
    Minimum Class Complexity                %10.2f
    Maximum Class Complexity                %10.2f
  Average Complexity per Method             %10.2f
    Minimum Method Complexity               %10.2f
    Maximum Method Complexity               %10.2f

Dependencies
  Global Accesses                           %10d
    Global Constants                        %10d (%.2f%%)
    Global Variables                        %10d (%.2f%%)
    Super-Global Variables                  %10d (%.2f%%)
  Attribute Accesses                        %10d
    Non-Static                              %10d (%.2f%%)
    Static                                  %10d (%.2f%%)
  Method Calls                              %10d
    Non-Static                              %10d (%.2f%%)
    Static                                  %10d (%.2f%%)

Structure
  Namespaces                                %10d
  Interfaces                                %10d
  Traits                                    %10d
  Classes                                   %10d
    Abstract Classes                        %10d (%.2f%%)
    Concrete Classes                        %10d (%.2f%%)
  Methods                                   %10d
    Scope
      Non-Static Methods                    %10d (%.2f%%)
      Static Methods                        %10d (%.2f%%)
    Visibility
      Public Methods                        %10d (%.2f%%)
      Non-Public Methods                    %10d (%.2f%%)
  Functions                                 %10d
    Named Functions                         %10d (%.2f%%)
    Anonymous Functions                     %10d (%.2f%%)
  Constants                                 %10d
    Global Constants                        %10d (%.2f%%)
    Class Constants                         %10d (%.2f%%)

END;

        $output->write(
            \sprintf(
                $format,
                $count['loc'],
                $count['cloc'],
                $count['loc'] > 0 ? ($count['cloc'] / $count['loc']) * 100 : 0,
                $count['ncloc'],
                $count['loc'] > 0 ? ($count['ncloc'] / $count['loc']) * 100 : 0,
                $count['lloc'],
                $count['loc'] > 0 ? ($count['lloc'] / $count['loc']) * 100 : 0,
                $count['llocClasses'],
                $count['lloc'] > 0 ? ($count['llocClasses'] / $count['lloc']) * 100 : 0,
                $count['classLlocAvg'],
                $count['classLlocMin'],
                $count['classLlocMax'],
                $count['methodLlocAvg'],
                $count['methodLlocMin'],
                $count['methodLlocMax'],
                $count['llocFunctions'],
                $count['lloc'] > 0 ? ($count['llocFunctions'] / $count['lloc']) * 100 : 0,
                $count['llocByNof'],
                $count['llocGlobal'],
                $count['lloc'] > 0 ? ($count['llocGlobal'] / $count['lloc']) * 100 : 0,
                $count['ccnByLloc'],
                $count['classCcnAvg'],
                $count['classCcnMin'],
                $count['classCcnMax'],
                $count['methodCcnAvg'],
                $count['methodCcnMin'],
                $count['methodCcnMax'],
                $count['globalAccesses'],
                $count['globalConstantAccesses'],
                $count['globalAccesses'] > 0 ? ($count['globalConstantAccesses'] / $count['globalAccesses']) * 100 : 0,
                $count['globalVariableAccesses'],
                $count['globalAccesses'] > 0 ? ($count['globalVariableAccesses'] / $count['globalAccesses']) * 100 : 0,
                $count['superGlobalVariableAccesses'],
                $count['globalAccesses'] > 0 ? ($count['superGlobalVariableAccesses'] / $count['globalAccesses']) * 100 : 0,
                $count['attributeAccesses'],
                $count['instanceAttributeAccesses'],
                $count['attributeAccesses'] > 0 ? ($count['instanceAttributeAccesses'] / $count['attributeAccesses']) * 100 : 0,
                $count['staticAttributeAccesses'],
                $count['attributeAccesses'] > 0 ? ($count['staticAttributeAccesses'] / $count['attributeAccesses']) * 100 : 0,
                $count['methodCalls'],
                $count['instanceMethodCalls'],
                $count['methodCalls'] > 0 ? ($count['instanceMethodCalls'] / $count['methodCalls']) * 100 : 0,
                $count['staticMethodCalls'],
                $count['methodCalls'] > 0 ? ($count['staticMethodCalls'] / $count['methodCalls']) * 100 : 0,
                $count['namespaces'],
                $count['interfaces'],
                $count['traits'],
                $count['classes'],
                $count['abstractClasses'],
                $count['classes'] > 0 ? ($count['abstractClasses'] / $count['classes']) * 100 : 0,
                $count['concreteClasses'],
                $count['classes'] > 0 ? ($count['concreteClasses'] / $count['classes']) * 100 : 0,
                $count['methods'],
                $count['nonStaticMethods'],
                $count['methods'] > 0 ? ($count['nonStaticMethods'] / $count['methods']) * 100 : 0,
                $count['staticMethods'],
                $count['methods'] > 0 ? ($count['staticMethods'] / $count['methods']) * 100 : 0,
                $count['publicMethods'],
                $count['methods'] > 0 ? ($count['publicMethods'] / $count['methods']) * 100 : 0,
                $count['nonPublicMethods'],
                $count['methods'] > 0 ? ($count['nonPublicMethods'] / $count['methods']) * 100 : 0,
                $count['functions'],
                $count['namedFunctions'],
                $count['functions'] > 0 ? ($count['namedFunctions'] / $count['functions']) * 100 : 0,
                $count['anonymousFunctions'],
                $count['functions'] > 0 ? ($count['anonymousFunctions'] / $count['functions']) * 100 : 0,
                $count['constants'],
                $count['globalConstants'],
                $count['constants'] > 0 ? ($count['globalConstants'] / $count['constants']) * 100 : 0,
                $count['classConstants'],
                $count['constants'] > 0 ? ($count['classConstants'] / $count['constants']) * 100 : 0
            )
        );

        if ($printTests) {
            $output->write(
                \sprintf(
                    "\nTests\n" .
                    "  Classes                                   %10d\n" .
                    "  Methods                                   %10d\n",
                    $count['testClasses'],
                    $count['testMethods']
                )
            );
        }
    }
}
