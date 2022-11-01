<?php

declare(strict_types=1);

namespace DDDStarterPack\Query;

use DDDStarterPack\Event\DomainEvent;

/**
 * @template T of DomainEvent
 */
interface EventProjector
{
    /**
     * @param T $event
     */
    public function project($event): void;

    /**
     * @param T $event
     */
    public function supports($event): bool;
}
