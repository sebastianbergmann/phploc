<?php

class SomeClass
{
    public function someFunction($in)
    {
        function () use ($in) {
            return '';
        };
    }

    public function someOtherFunction()
    {
        //trigger Undefined index: ccn
        return false || true;
    }
}
