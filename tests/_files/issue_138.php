<?php

class Issue138
{
    public function first()
    {
        new class() {
        };
    }

    public function second()
    {
        new class() {
            public $x;
        };
    }
}
