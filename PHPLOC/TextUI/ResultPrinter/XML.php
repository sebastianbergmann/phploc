<?php
/**
 * phploc
 *
 * Copyright (c) 2009, Sebastian Bergmann <sb@sebastian-bergmann.de>.
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
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @since     File available since Release 1.1.0
 */

/**
 * An XML ResultPrinter for the TextUI.
 *
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://github.com/sebastianbergmann/phploc/tree
 * @since     Class available since Release 1.1.0
 */
class PHPLOC_TextUI_ResultPrinter_XML
{
    /**
     * Prints a result set.
     *
     * @param string $filename
     * @param array  $countSut
     * @param array  $countTests
     */
    public function printResult($filename, array $countSut, array $countTests)
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = TRUE;

        $root = $document->createElement('phploc');
        $document->appendChild($root);

        if (empty($countTests)) {
            $this->processArray($document, $root, $countSut);
        } else {
            $sutElement = $document->createElement('sut');
            $root->appendChild($sutElement);

            $testsElement = $document->createElement('tests');
            $root->appendChild($testsElement);

            $this->processArray($document, $sutElement, $countSut);
            $this->processArray($document, $testsElement, $countTests);
        }

        file_put_contents($filename, $document->saveXML());
    }

    protected function processArray(DOMDocument $document, DOMElement $element, array $count)
    {
        if ($count['directories'] > 0) {
            $element->appendChild(
              $document->createElement('directories', $count['directories'])
            );

            $element->appendChild(
              $document->createElement('files', $count['files'])
            );
        }

        $element->appendChild(
          $document->createElement('loc', $count['loc'])
        );

        if (isset($count['eloc'])) {
            $element->appendChild(
              $document->createElement('eloc', $count['eloc'])
            );
        }

        $element->appendChild(
          $document->createElement('cloc', $count['cloc'])
        );

        $element->appendChild(
          $document->createElement('ncloc', $count['ncloc'])
        );

        $element->appendChild(
          $document->createElement('interfaces', $count['interfaces'])
        );

        $element->appendChild(
          $document->createElement('classes', $count['classes'])
        );

        $element->appendChild(
          $document->createElement('functionsAndMethods', $count['functions'])
        );

        return $element;
    }
}
?>
