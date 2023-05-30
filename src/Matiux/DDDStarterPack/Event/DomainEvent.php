<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DDDStarterPack\Identity\AggregateId;
use DDDStarterPack\Identity\Trace\DomainTrace;
use DDDStarterPack\Type\DateTimeRFC;

/**
 * @template-covariant I of AggregateId
 */
abstract readonly class DomainEvent
{
    public string $eventName;

    /**
     * @param I $aggregateId
     */
    protected function __construct(
        public EventId $eventId,
        public mixed $aggregateId,
        public DomainTrace $domainTrace,
        public DomainEventVersion $version,
        public DateTimeRFC $occurredAt,
    ) {
        $this->eventName = strtolower(
            preg_replace(
                '/(?<!^)[A-Z]/',
                '_$0',
                (new \ReflectionClass($this))->getShortName(),
            ),
        );
    }

    public function serialize(): array
    {
        return [
            'event_id' => $this->eventId->value(),
            'aggregate_id' => $this->aggregateId->value(),
            'event_payload' => $this->serializeEventPayload(),
            'event_version' => $this->version->v,
            'domain_trace' => [
                'correlation_id' => $this->domainTrace->correlationId->value(),
                'causation_id' => $this->domainTrace->causationId->value(),
            ],
            'occurred_at' => $this->occurredAt->value(),
        ];
    }

    abstract protected function serializeEventPayload(): array;

    abstract public function enrich(EnrichOptions $enrichOptions): self;
}
