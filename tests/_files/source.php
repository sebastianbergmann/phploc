<?php
namespace a\name\space;

/**
 * A comment.
 */

define('A_GLOBAL_CONSTANT', 'foo');

function &a_global_function()
{
    $a = AClass::CLASS;
}

interface AnInterface
{
}

abstract class AnAbstractClass
{
}

class AClass extends AnAbstractClass implements AnInterface
{
    const A_CLASS_CONSTANT = 'bar';

    private static $a = array();

    public static function aStaticMethod()
    {
        global $foo;

        $a = $_GET['a'];
        $GLOBALS['bar'] = A_GLOBAL_CONSTANT;

        $o->m();
        $o->$m();
        $o->a;
        $o->$a;
    }

    public function aPublicMethod()
    {
        $a = TRUE ? TRUE : FALSE;

        c::m();
        c::$m();
        c::$a;
        c::$a;
        c::aConstant;
    }

    protected function aProtectedMethod()
    {
        if (TRUE) {
        }

        $c::m();
        $c::$m();
        $c::$a;
        $c::$a;
    }

    private function aPrivateMethod()
    {
        $function = function() {};
        echo "This is {$great}";
        echo "This is ${great}";
    }
}
