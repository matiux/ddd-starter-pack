<?php

namespace DddStarterPack\Domain\Model\Event;

use DddStarterPack\Domain\Event\DomainEvent;

/**
 * Questa entitità rappresenta un evento persistito
 *
 * Class StoredEvent
 * @package DddStarterPack\Domain\Model\Event
 */
class StoredEvent implements DomainEvent
{
    private $eventId;
    private $eventBody;
    private $occurredOn;
    private $typeName;

    public function __construct(string $aTypeName, \DateTimeImmutable $anOccurredOn, string $anEventBody)
    {
        $this->eventBody = $anEventBody;
        $this->typeName = $aTypeName;
        $this->occurredOn = $anOccurredOn;
    }

    public function eventBody(): string
    {
        return $this->eventBody;
    }

    public function eventId(): int
    {
        return $this->eventId;
    }

    public function typeName(): string
    {
        return $this->typeName;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
