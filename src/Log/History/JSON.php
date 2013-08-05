<?php
/**
 * phploc
 *
 * Copyright (c) 2009-2013, Sebastian Bergmann <sebastian@phpunit.de>.
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
 * @author    Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright 2009-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @since     File available since Release 2.0.0
 */

namespace SebastianBergmann\PHPLOC\Log\History
{
    /**
     * A CSV ResultPrinter for the TextUI.
     *
     * @author    Sebastian Bergmann <sebastian@phpunit.de>
     * @copyright 2009-2013 Sebastian Bergmann <sebastian@phpunit.de>
     * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
     * @link      http://github.com/sebastianbergmann/phploc/tree
     * @since     Class available since Release 2.0.0
     */
    class JSON
    {
        /**
         * Prints a result set.
         *
         * @param string $filename
         * @param array  $count
         */
        public function printResult($filename, array $count)
        {
            $buffer = array();

            foreach ($count as $date => $data) {
                $buffer[] = array(
                  $date,
                  $data['directories'],
                  $data['files'],
                  $data['loc'],
                  $data['ccnByLloc'],
                  $data['cloc'],
                  $data['ncloc'],
                  $data['lloc'],
                  $data['namespaces'],
                  $data['interfaces'],
                  $data['traits'],
                  $data['classes'],
                  $data['abstractClasses'],
                  $data['concreteClasses'],
                  $data['llocByNoc'],
                  $data['methods'],
                  $data['nonStaticMethods'],
                  $data['staticMethods'],
                  $data['publicMethods'],
                  $data['nonPublicMethods'],
                  $data['llocByNom'],
                  $data['ccnByNom'],
                  $data['functions'],
                  $data['namedFunctions'],
                  $data['anonymousFunctions'],
                  $data['constants'],
                  $data['globalConstants'],
                  $data['classConstants'],
                  $data['attributeAccesses'],
                  $data['instanceAttributeAccesses'],
                  $data['staticAttributeAccesses'],
                  $data['methodCalls'],
                  $data['instanceMethodCalls'],
                  $data['staticMethodCalls'],
                  $data['globalAccesses'],
                  $data['globalVariableAccesses'],
                  $data['superGlobalVariableAccesses'],
                  $data['globalConstantAccesses'],
                  $data['testClasses'],
                  $data['testMethods']
                );
            }

            file_put_contents($filename, json_encode($buffer));
        }
    }
}
