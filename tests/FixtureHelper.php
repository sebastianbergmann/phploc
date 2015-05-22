<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Markus Schulte <email@markusschulte.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class FixtureHelper
{
    /**
     * @return array
     */
    public static function getSampleRow() {
        $analyser_count_property = new ReflectionProperty('\SebastianBergmann\PHPLOC\Analyser', 'count');
        $analyser_count_property->setAccessible(true);

        $analyser = new \SebastianBergmann\PHPLOC\Analyser();
        $count = $analyser_count_property->getValue($analyser);

        // Remove "internal" metrics from fixture.
        unset($count['ccn']);
        unset($count['ccnMethods']);

        $i = 0;
        foreach (array_keys($count) as $metric_name) {
            $count[$metric_name] = ++$i;
        }

        return $count;
    }
}
