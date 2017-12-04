<?php

namespace DDDStarterPack\Domain\Model\Event;

interface StoredDomainEvent extends DomainEvent
{
    public function eventBody(): string;

    public function eventId(): ?int;

    public function typeName(): string;
}
