<?php

namespace SebastianBergmann\PHPLOC;

class Publisher
{
    private $counts;

    public function __construct(array $counts)
    {
        $this->counts = $counts;
    }

    public function getDirectories()
    {
        return $this->getCount('directories') - 1;
    }

    public function getFiles()
    {
        return $this->getValue('files');
    }

    public function toArray()
    {
        return [
            'files' => $this->getFiles(),
            'directories' => $this->getDirectories(),
        ];
    }

    private function getCount($key)
    {
        return isset($this->counts[$key]) ? count($this->counts[$key]) : 0;
    }

    private function getValue($key)
    {
        return isset($this->counts[$key]) ? $this->counts[$key] : 0;
    }
}
