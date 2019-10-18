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
     */
    public function printResult($filename, array $count): void
    {
        \file_put_contents(
            $filename,
            \json_encode($count, \JSON_PRETTY_PRINT)
        );
    }
}
