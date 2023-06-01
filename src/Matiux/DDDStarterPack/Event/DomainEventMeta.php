<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DDDStarterPack\Identity\Trace\DomainTrace;

final readonly class DomainEventMeta
{
    public function __construct(
        public EventId $eventId,
        public DomainTrace $domainTrace,
        public DomainEventVersion $version,
    ) {
    }

    public function toArray(): array
    {
        return [
            'event_id' => $this->eventId->value(),
            'correlation_id' => $this->domainTrace->correlationId->value(),
            'causation_id' => $this->domainTrace->causationId->value(),
            'event_version' => $this->version->v,
        ];
    }
}
