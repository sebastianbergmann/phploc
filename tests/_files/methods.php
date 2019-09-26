<?php

class Methods1
{
    function undefinedVisibilityBecomesPublicVisibility1()
    {
    }

    public function publicVisibility2()
    {
    }

    protected /* a comment here */ function protectedVisibility1()
    {
    }

    private function privateVisibility1()
    {
    }
}

class Methods2
{
    private function privateVisibility2()
    {
    }

    private /* a comment here */ function privateVisibility3()
    {
    }
}
