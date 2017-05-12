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
 * An XML ResultPrinter for the TextUI.
 */
class Xml
{
    /**
     * Prints a result set.
     *
     * @param string $filename
     * @param array  $count
     */
    public function printResult($filename, array $count)
    {
        $document               = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = true;

        $root = $document->createElement('phploc');
        $document->appendChild($root);

        if ($count['directories'] > 0) {
            $root->appendChild(
                $document->createElement('directories', $count['directories'])
            );

            $root->appendChild(
                $document->createElement('files', $count['files'])
            );
        }

        unset($count['directories']);
        unset($count['files']);

        foreach ($count as $k => $v) {
            $root->appendChild(
                $document->createElement($k, $v)
            );
        }

        \file_put_contents($filename, $document->saveXML());
    }
}
