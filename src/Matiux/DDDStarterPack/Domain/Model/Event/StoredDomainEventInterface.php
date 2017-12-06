<?php

namespace DDDStarterPack\Domain\Model\Event;

interface StoredDomainEventInterface
{
    public function eventBody(): string;

    public function eventId(): ?int;

    public function typeName(): string;

    public function occurredOn(): \DateTimeImmutable;
}
