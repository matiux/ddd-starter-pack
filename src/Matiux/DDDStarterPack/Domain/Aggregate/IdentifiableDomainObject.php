<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate;

interface IdentifiableDomainObject
{
    /**
     * @return EntityId
     */
    public function id();
}
