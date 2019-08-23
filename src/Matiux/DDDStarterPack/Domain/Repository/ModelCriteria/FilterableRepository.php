<?php

namespace DDDStarterPack\Domain\Repository\ModelCriteria;

use ArrayObject;

interface FilterableRepository
{
    public function byCriteria(?ModelCriteria $modelCriteria): ArrayObject;

    public function count(ModelCriteria $modelCriteria): int;
}
