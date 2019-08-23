<?php

namespace DDDStarterPack\Domain\Repository\ModelCriteria;

use DDDStarterPack\Domain\Aggregate\EntityId;

interface IdentifiableRepository
{
    public function ofId($id);

    public function nextIdentity(): EntityId;
}
