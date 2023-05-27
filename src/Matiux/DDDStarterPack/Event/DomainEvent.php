<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DDDStarterPack\Identity\AggregateId;
use DDDStarterPack\Identity\Trace\DomainTrace;
use DDDStarterPack\Type\DateTimeRFC;

abstract readonly class DomainEvent
{
    public string $eventName;

    public function __construct(
        public EventId $eventId,
        public AggregateId $aggregateId,
        public DomainTrace $domainTrace,
        public DateTimeRFC $occurredAt,
    ) {
        $this->eventName = (new \ReflectionClass($this))->getShortName();
    }

    public function serialize(): array
    {
        return [
            'event_id' => $this->eventId->value(),
            'aggregate_id' => $this->aggregateId->value(),
            'event_data' => $this->serializeEventData(),
            'domain_trace' => [
                'correlation_id' => $this->domainTrace->correlationId,
                'causation_id' => $this->domainTrace->causationId,
            ],
            'occurred_at' => $this->occurredAt->value(),
        ];
    }

    abstract protected function serializeEventData(): array;
}
