<?php

namespace Tests\DDDStarterPack\Infrastructure\Domain\Model\Event;

use DDDStarterPack\Domain\Model\Event\DomainEvent;

class FakeDomainEvent implements DomainEvent
{
    private $entityId;
    private $occuredOn;

    public function __construct(string $entityId)
    {
        $this->entityId = $entityId;
        $this->occuredOn = new \DateTimeImmutable();
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occuredOn;
    }

    public function entityId()
    {
        return $this->entityId;
    }
}
