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
        return [
            'directories' => 1,
            'files' => 2,
            'loc' => 3,
            'ccnByLloc' => 4,
            'cloc' => 5,
            'ncloc' => 6,
            'lloc' => 7,
            'llocGlobal' => 8,
            'namespaces' => 9,
            'interfaces' => 10,
            'traits' => 11,
            'classes' => 12,
            'abstractClasses' => 13,
            'concreteClasses' => 14,
            'llocClasses' => 15,
            'methods' => 16,
            'nonStaticMethods' => 17,
            'staticMethods' => 18,
            'publicMethods' => 19,
            'nonPublicMethods' => 20,
            'classLlocAvg' => 21,
            'classLlocMin' => 22,
            'classLlocMax' => 23,
            'classCcnAvg' => 24,
            'classCcnMin' => 25,
            'classCcnMax' => 26,
            'methodLlocAvg' => 27,
            'methodLlocMin' => 28,
            'methodLlocMax' => 29,
            'methodCcnAvg' => 30,
            'methodCcnMin' => 31,
            'methodCcnMax' => 32,
            'functions' => 33,
            'namedFunctions' => 34,
            'anonymousFunctions' => 35,
            'llocFunctions' => 36,
            'llocByNof' => 37,
            'constants' => 38,
            'globalConstants' => 39,
            'classConstants' => 40,
            'attributeAccesses' => 41,
            'instanceAttributeAccesses' => 42,
            'staticAttributeAccesses' => 43,
            'methodCalls' => 44,
            'instanceMethodCalls' => 45,
            'staticMethodCalls' => 46,
            'globalAccesses' => 47,
            'globalVariableAccesses' => 48,
            'superGlobalVariableAccesses' => 49,
            'globalConstantAccesses' => 50,
            'testClasses' => 51,
            'testMethods' => 52,
        ];
    }
}
