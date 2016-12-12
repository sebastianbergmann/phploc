<?php

namespace SebastianBergmann\PHPLOC;

class Collector
{
    private $counts = [];

    public function getPublisher()
    {
        return new Publisher($this->counts);
    }

    public function addFile($filename)
    {
        $this->increment('files');
        $this->addUnique('directories', dirname($filename));
    }

    private function addUnique($key, $name)
    {
        $this->check($key, []);
        $this->counts[$key][$name] = true;
    }

    private function increment($key)
    {
        $this->check($key, 0);
        $this->counts[$key]++;
    }

    private function check($key, $default)
    {
        if (!isset($this->counts[$key])) {
            $this->counts[$key] = $default;
        }
    }
}
