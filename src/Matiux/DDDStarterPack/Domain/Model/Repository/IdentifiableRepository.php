<?php

namespace DDDStarterPack\Domain\Model\Repository;

interface IdentifiableRepository
{
    public function ofId($id);

    public function nextIdentity();
}
