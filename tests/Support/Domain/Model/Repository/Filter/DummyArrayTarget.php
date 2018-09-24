<?php

namespace Tests\DDDStarterPack\Support\Domain\Model\Repository\Filter;

class DummyArrayTarget
{
    private $data = [];

    public function add($item)
    {
        $this->data[] = $item;
    }

    public function get(): array
    {
        return $this->data;
    }
}
