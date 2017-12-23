<?php

namespace Tests\DDDStarterPack\Infrastructure\Domain\Model\Event;

use DDDStarterPack\Domain\Model\Event\DomainEvent;

class FakeDomainEvent implements DomainEvent
{
    private $entityId;
    private $occurredOn;

    public function __construct(string $entityId)
    {
        $this->entityId = $entityId;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function entityId()
    {
        return $this->entityId;
    }
}
