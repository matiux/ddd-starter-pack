<?php

namespace DddStarterPack\Domain\Model\Exception\Repository;

interface FilterableRepository
{
    public function byCriteria(?ModelCriteria $modelCriteria): \ArrayObject;

    public function count(ModelCriteria $modelCriteria): int;
}
