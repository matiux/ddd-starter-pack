<?php

namespace DddStarterPack\Domain\Model\Event;

/**
 * Questa entitità rappresenta un evento persistito
 *
 * Class StoredEvent
 * @package DddStarterPack\Domain\Model\EventSystem
 */
class StoredEvent implements Event
{
    private $eventId;
    private $eventBody;
    private $occurredOn;
    private $typeName;

    public function __construct(?int $eventId, string $aTypeName, \DateTimeImmutable $anOccurredOn, string $anEventBody)
    {
        $this->eventId = $eventId;
        $this->eventBody = $anEventBody;
        $this->typeName = $aTypeName;
        $this->occurredOn = $anOccurredOn;
    }

    public function eventBody(): string
    {
        return $this->eventBody;
    }

    public function eventId(): ?int
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
