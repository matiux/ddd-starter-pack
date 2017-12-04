<?php

namespace DDDStarterPack\Domain\Model\Repository;

interface FilterableRepository
{
    public function byCriteria(?ModelCriteria $modelCriteria): \ArrayObject;

    public function count(ModelCriteria $modelCriteria): int;
}
