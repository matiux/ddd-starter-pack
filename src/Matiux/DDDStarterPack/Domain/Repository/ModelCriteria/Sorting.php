<?php

namespace DDDStarterPack\Domain\Aggregate\Repository\ModelCriteria;

class Sorting
{
    private $field;
    private $sorting;

    public function __construct(string $field, string $sorting)
    {
        $this->field = $field;
        $this->sorting = $sorting;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getSorting(): string
    {
        return $this->sorting;
    }
}
