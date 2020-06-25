<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate\Repository\ModelCriteria;

use ArrayObject;

interface FilterableRepository
{
    public function byCriteria(?ModelCriteria $modelCriteria): ArrayObject;

    public function count(ModelCriteria $modelCriteria): int;
}
