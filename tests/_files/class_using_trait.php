<?php

class ClassUsingTrait
{
    use FooTrait;

    public function bar()
    {
        return 1;
    }
}
