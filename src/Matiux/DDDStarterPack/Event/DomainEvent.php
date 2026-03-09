<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DDDStarterPack\Identity\AggregateId;
use DDDStarterPack\Type\DateTimeRFC;

/**
 * @template-covariant I of AggregateId
 *
 * @psalm-type SerializedMeta = array{
 *   event_id: string,
 *   event_version: int,
 *   context: null|string,
 *   domain_trace: array{correlation_id: string, causation_id: string}
 * }
 * @psalm-type SerializedDomainEvent = array{
 *   event_name: string,
 *   aggregate_id: string,
 *   event_payload: array,
 *   occurred_at: string,
 *   meta: SerializedMeta
 * }
 */
abstract readonly class DomainEvent
{
    public string $eventName;

    /**
     * @param I $aggregateId
     */
    protected function __construct(
        public AggregateId $aggregateId,
        public DateTimeRFC $occurredAt,
        public DomainEventMeta $meta,
    ) {
        $name = strtolower(
            preg_replace(
                '/(?<!^)[A-Z]/',
                '_$0',
                (new \ReflectionClass($this))->getShortName(),
            ) ?? '', // Camel case to snake case
        );

        $path = "/_v{$this->meta->version->v}\$/";

        $this->eventName = preg_replace($path, '', $name) ?? ''; // Remove version from the end of the name
    }

    /** @return SerializedDomainEvent */
    public function serialize(): array
    {
        return [
            'event_name' => $this->eventName,
            'aggregate_id' => $this->aggregateId->value(),
            'event_payload' => $this->serializeEventPayload(),
            'occurred_at' => $this->occurredAt->value(),
            'meta' => $this->meta->serialize(),
        ];
    }

    abstract protected function serializeEventPayload(): array;

    abstract public function enrich(EnrichOptions $enrichOptions): self;

    protected function enrichedDomainEventMeta(EnrichOptions $enrichOptions): DomainEventMeta
    {
        return new DomainEventMeta(
            $enrichOptions->eventId ?? $this->meta->eventId,
            $enrichOptions->domainTrace,
            $this->meta->version,
            $enrichOptions->context ?? $this->meta->context(),
        );
    }
}
