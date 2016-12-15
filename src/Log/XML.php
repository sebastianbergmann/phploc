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

/**
 * An XML ResultPrinter for the TextUI.
 *
 * @since     Class available since Release 1.1.0
 */
class XML
{
    /**
     * Prints a result set.
     *
     * @param string    $filename
     * @param Publisher $publisher
     */
    public function printResult($filename, Publisher $publisher)
    {
        $document               = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = true;

        $root = $document->createElement('phploc');
        $document->appendChild($root);

        foreach ($publisher->toArrayStartWithDirectories() as $k => $v) {
            $root->appendChild(
                $document->createElement($k, $v)
            );
        }

        file_put_contents($filename, $document->saveXML());
    }
}
