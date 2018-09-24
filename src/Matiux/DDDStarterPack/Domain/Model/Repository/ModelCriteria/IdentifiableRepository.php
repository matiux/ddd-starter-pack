<?php

namespace DDDStarterPack\Domain\Model\Repository\ModelCriteria;

interface IdentifiableRepository
{
    public function ofId($id);

    public function nextIdentity();
}
