<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

interface IdentifiableDomainEvent extends DomainEvent
{
    public function id(): string;
}
