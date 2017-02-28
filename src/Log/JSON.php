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

use SebastianBergmann\PHPLOC\Publisher;

class JSON
{
    /**
     * Prints a result set.
     *
     * @param string    $filename
     * @param Publisher $publisher
     */
    public function printResult($filename, Publisher $publisher)
    {
        file_put_contents($filename, json_encode($publisher->toArrayStartWithDirectories(), JSON_PRETTY_PRINT));
    }
}
