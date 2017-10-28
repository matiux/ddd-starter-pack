<?php

namespace DddStarterPack\Domain\Model\Exception\Repository;

class Criterion
{
    private $field;
    private $value;
    private $operator;

    public function __construct(string $field, string $value, string $operator = '=')
    {
        $this->field = $field;
        $this->value = $value;
        $this->operator = $operator;
    }

    public function field(): string
    {
        return $this->field;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function operator(): string
    {
        return $this->operator;
    }
}
