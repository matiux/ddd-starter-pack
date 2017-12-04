<?php

namespace DDDStarterPack\Domain\Model;

interface IdentifiableDomainObject
{
    /**
     * @return EntityId
     */
    public function id();
}
