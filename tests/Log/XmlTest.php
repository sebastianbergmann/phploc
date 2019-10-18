<?php
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\PHPLOC;

use PHPUnit\Framework\TestCase;

class XmlTest extends TestCase
{
    /**
     * @var \SebastianBergmann\PHPLOC\Log\Xml
     */
    private $single;

    private $sample_row = [
        'directories'                 => 1,
        'files'                       => 2,
        'loc'                         => 3,
        'ccnByLloc'                   => 4,
        'cloc'                        => 5,
        'ncloc'                       => 6,
        'lloc'                        => 7,
        'llocGlobal'                  => 8,
        'namespaces'                  => 9,
        'interfaces'                  => 10,
        'traits'                      => 11,
        'classes'                     => 12,
        'abstractClasses'             => 13,
        'concreteClasses'             => 14,
        'llocClasses'                 => 15,
        'methods'                     => 16,
        'nonStaticMethods'            => 17,
        'staticMethods'               => 18,
        'publicMethods'               => 19,
        'nonPublicMethods'            => 20,
        'methodCcnAvg'                => 21,
        'functions'                   => 22,
        'namedFunctions'              => 23,
        'anonymousFunctions'          => 24,
        'llocFunctions'               => 25,
        'llocByNof'                   => 26,
        'constants'                   => 27,
        'globalConstants'             => 28,
        'classConstants'              => 29,
        'attributeAccesses'           => 30,
        'instanceAttributeAccesses'   => 31,
        'staticAttributeAccesses'     => 32,
        'methodCalls'                 => 33,
        'instanceMethodCalls'         => 34,
        'staticMethodCalls'           => 35,
        'globalAccesses'              => 36,
        'globalVariableAccesses'      => 37,
        'superGlobalVariableAccesses' => 38,
        'globalConstantAccesses'      => 39,
        'testClasses'                 => 40,
        'testMethods'                 => 41,
        'classCcnAvg'                 => 42,
        'classLlocAvg'                => 43,
        'methodLlocAvg'               => 44,
    ];

    protected function setUp(): void
    {
        $this->single = new \SebastianBergmann\PHPLOC\Log\Xml;
    }

    public function testPrintedResult(): void
    {
        \ob_start();

        $this->single->printResult('php://output', $this->sample_row);
        $output = \ob_get_clean();

        $expected = <<<END
<?xml version="1.0" encoding="UTF-8"?>
<phploc>
  <directories>1</directories>
  <files>2</files>
  <loc>3</loc>
  <ccnByLloc>4</ccnByLloc>
  <cloc>5</cloc>
  <ncloc>6</ncloc>
  <lloc>7</lloc>
  <llocGlobal>8</llocGlobal>
  <namespaces>9</namespaces>
  <interfaces>10</interfaces>
  <traits>11</traits>
  <classes>12</classes>
  <abstractClasses>13</abstractClasses>
  <concreteClasses>14</concreteClasses>
  <llocClasses>15</llocClasses>
  <methods>16</methods>
  <nonStaticMethods>17</nonStaticMethods>
  <staticMethods>18</staticMethods>
  <publicMethods>19</publicMethods>
  <nonPublicMethods>20</nonPublicMethods>
  <methodCcnAvg>21</methodCcnAvg>
  <functions>22</functions>
  <namedFunctions>23</namedFunctions>
  <anonymousFunctions>24</anonymousFunctions>
  <llocFunctions>25</llocFunctions>
  <llocByNof>26</llocByNof>
  <constants>27</constants>
  <globalConstants>28</globalConstants>
  <classConstants>29</classConstants>
  <attributeAccesses>30</attributeAccesses>
  <instanceAttributeAccesses>31</instanceAttributeAccesses>
  <staticAttributeAccesses>32</staticAttributeAccesses>
  <methodCalls>33</methodCalls>
  <instanceMethodCalls>34</instanceMethodCalls>
  <staticMethodCalls>35</staticMethodCalls>
  <globalAccesses>36</globalAccesses>
  <globalVariableAccesses>37</globalVariableAccesses>
  <superGlobalVariableAccesses>38</superGlobalVariableAccesses>
  <globalConstantAccesses>39</globalConstantAccesses>
  <testClasses>40</testClasses>
  <testMethods>41</testMethods>
  <classCcnAvg>42</classCcnAvg>
  <classLlocAvg>43</classLlocAvg>
  <methodLlocAvg>44</methodLlocAvg>
</phploc>
END;

        $this->assertEquals(\trim($expected), \trim($output));
    }

    public function testPrintedResultWithNoSubDirectories(): void
    {
        \ob_start();

        $this->single->printResult('php://output', array_merge($this->sample_row, ['directories' => 0]));
        $output = \ob_get_clean();

        $expected = <<<END
<?xml version="1.0" encoding="UTF-8"?>
<phploc>
  <directories>0</directories>
  <files>2</files>
  <loc>3</loc>
  <ccnByLloc>4</ccnByLloc>
  <cloc>5</cloc>
  <ncloc>6</ncloc>
  <lloc>7</lloc>
  <llocGlobal>8</llocGlobal>
  <namespaces>9</namespaces>
  <interfaces>10</interfaces>
  <traits>11</traits>
  <classes>12</classes>
  <abstractClasses>13</abstractClasses>
  <concreteClasses>14</concreteClasses>
  <llocClasses>15</llocClasses>
  <methods>16</methods>
  <nonStaticMethods>17</nonStaticMethods>
  <staticMethods>18</staticMethods>
  <publicMethods>19</publicMethods>
  <nonPublicMethods>20</nonPublicMethods>
  <methodCcnAvg>21</methodCcnAvg>
  <functions>22</functions>
  <namedFunctions>23</namedFunctions>
  <anonymousFunctions>24</anonymousFunctions>
  <llocFunctions>25</llocFunctions>
  <llocByNof>26</llocByNof>
  <constants>27</constants>
  <globalConstants>28</globalConstants>
  <classConstants>29</classConstants>
  <attributeAccesses>30</attributeAccesses>
  <instanceAttributeAccesses>31</instanceAttributeAccesses>
  <staticAttributeAccesses>32</staticAttributeAccesses>
  <methodCalls>33</methodCalls>
  <instanceMethodCalls>34</instanceMethodCalls>
  <staticMethodCalls>35</staticMethodCalls>
  <globalAccesses>36</globalAccesses>
  <globalVariableAccesses>37</globalVariableAccesses>
  <superGlobalVariableAccesses>38</superGlobalVariableAccesses>
  <globalConstantAccesses>39</globalConstantAccesses>
  <testClasses>40</testClasses>
  <testMethods>41</testMethods>
  <classCcnAvg>42</classCcnAvg>
  <classLlocAvg>43</classLlocAvg>
  <methodLlocAvg>44</methodLlocAvg>
</phploc>
END;

        $this->assertEquals(\trim($expected), \trim($output));
    }

}
