<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain;

/**
 * @template T of BasicEntityId
 */
interface IdentifiableDomainObject
{
    /**
     * @return T
     */
    public function id();
}
