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
 * @author    Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright Sebastian Bergmann <sebastian@phpunit.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link      http://github.com/sebastianbergmann/phploc/tree
 * @since     Class available since Release 2.0.0
 */
class History extends Single
{
    /**
     * Prints a result set.
     *
     * @param string $filename
     * @param array  $count
     */
    public function printResult($filename, array $count)
    {
        $buffer = $this->getKeysLine($count);

        foreach ($count as $date => $data) {
            $buffer .= $date . ',' . $this->getValuesLine($data);
        }

        file_put_contents($filename, $buffer);
    }

    /**
     * @param  array $count
     * @return string
     */
    protected function getValuesLine(array $count)
    {
        $values = array(
            'commit' => $count['commit'],
        );

        return '"' . implode('","', $values) . '",' . parent::getValuesLine($count);
    }

    /**
     * @param  array $count
     * @return string
     */
    protected function getKeysLine(array $count)
    {
        $keys = array(
            'Date',
            'Commit',
        );
        return implode(',', $keys) . ',' . parent::getKeysLine($count);
    }
}
