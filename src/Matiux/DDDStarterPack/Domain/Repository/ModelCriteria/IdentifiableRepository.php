<?php

namespace DDDStarterPack\Domain\Aggregate\Repository\ModelCriteria;

use DDDStarterPack\Domain\Aggregate\EntityId;

interface IdentifiableRepository
{
    public function ofId($id);

    public function nextIdentity(): EntityId;
}
