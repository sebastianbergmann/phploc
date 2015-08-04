<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\PHPLOC\Log\CSV;

/**
 * A CSV ResultPrinter for the TextUI.
 *
 * @since     Class available since Release 2.0.0
 */
class History extends Single
{

    /**
     * @var resource The file handle for this instance
     */
    protected $file;

    /**
     * @var bool Is the file initialized
     */
    protected $isInitialized = false;

    /**
     * Construct the history printer
     *
     * @param string $filename The name of the file to write to
     */
    public function __construct($filename)
    {
        $this->file = fopen($filename, 'w+');
        if (!$this->file) {
            throw new \RuntimeException("Could not open file for writing");
        }
    }

    /**
     * Print a single row to the output file
     *
     * @param array $data A single row of data
     */
    public function printRow(array $data)
    {
        if (!$this->isInitialized) {
            $this->isInitialized = true;
            fwrite($this->file, $this->getKeysLine($data));
        }
        fwrite($this->file, $this->getValuesLine($data));
    }

    /**
     * @param  array  $count
     * @return string
     */
    protected function getValuesLine(array $count)
    {
        $values = [
            'commit' => $count['commit'],
        ];

        return $count['date'] . ',"' . implode('","', $values) . '",' . parent::getValuesLine($count);
    }

    /**
     * @param  array  $count
     * @return string
     */
    protected function getKeysLine(array $count)
    {
        $keys = ['Date', 'Commit'];

        return implode(',', $keys) . ',' . parent::getKeysLine($count);
    }
}
