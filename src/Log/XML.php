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
 *
 * @since     Class available since Release 1.1.0
 */
class XML
{
    /**
     * @var \DOMDocument
     */
    protected $document;

    /**
     * Prints a result set from scratch.
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

        file_put_contents($filename, $document->saveXML());
    }

    /**
     * Get the XML document that we are working on.
     *
     * @param $filename
     * @return \DOMDocument
     * @throws \DOMException
     */
    private function getDocument($filename)
    {
        static $createdFile = false;

        if($this->document instanceof \DOMDocument) {
            return $this->document;
        }

        if($createdFile === true && file_exists($filename)) {
            $document = new \DOMDocument();

            if($document->load($filename)) {
                $this->document = $document;

                return $this->document;
            } else {
                throw new \DOMException();
            }
        }

        $document               = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = true;

        $root = $document->createElement('phploc');
        $document->appendChild($root);

        file_put_contents($filename, $document->saveXML());
        $createdFile = true;

        $this->document = $document;

        return $this->document;
    }

    /**
     * Adds a new result set to the XML file.
     *
     * @param $filename
     * @param array $count
     * @throws \DOMException
     */
    public function addResult($filename, array $count)
    {
        $document = $this->getDocument($filename);
        $root = $document->getElementsByTagName('phploc');

        if($root->length < 1) {
            throw new \DOMException('Could not find phploc element in the XML file.');
        }

        $root = $root->item(0);

        if(isset($count['project_directory'])) {
            $project = $document->createElement('project');
            $project->setAttribute('directory', $count['project_directory']);

            $root = $root->appendChild($project);
        }

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

        file_put_contents($filename, $document->saveXML());
    }

}
