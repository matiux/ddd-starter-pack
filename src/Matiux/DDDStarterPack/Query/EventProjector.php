<?php

declare(strict_types=1);

namespace DDDStarterPack\Query;

use DDDStarterPack\Event\DomainEvent;

/**
 * @template T of DomainEvent
 */
interface Projector
{
    /**
     * @param T $event
     */
    public function project($event): void;
}
