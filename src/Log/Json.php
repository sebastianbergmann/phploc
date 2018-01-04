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

/**
 * An JSON ResultPrinter for the TextUI.
 */
class Json
{
    /**
     * Prints a result set.
     *
     * @param string $filename
     * @param array  $count
     */
    public function printResult($filename, array $count)
    {
        $directories = [];

        if ($count['directories'] > 0) {
            $directories = [
                'directories' => $count['directories'],
                'files'       => $count['files'],
            ];
        }

        unset($count['directories']);
        unset($count['files']);

        $report = \array_merge($directories, $count);

        \file_put_contents(
            $filename,
            \json_encode($report, JSON_PRETTY_PRINT)
        );
    }
}
