<?php

namespace DddStarterPack\Domain\Model\Exception\Repository;

class Sorting
{
    private $field;
    private $sorting;

    public function __construct(string $field, string $sorting)
    {
        $this->field = $field;
        $this->sorting = $sorting;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getSorting()
    {
        return $this->sorting;
    }
}
