<?php

namespace DDDStarterPack\Domain\Aggregate;

interface IdentifiableDomainObject
{
    /**
     * @return EntityId
     */
    public function id();
}
