<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DDDStarterPack\Identity\AggregateId;
use DDDStarterPack\Identity\Trace\CausationId;
use DDDStarterPack\Identity\Trace\CorrelationId;
use DDDStarterPack\Identity\Trace\DomainTrace;
use DDDStarterPack\Type\DateTimeRFC;

/**
 * @template-covariant I of AggregateId
 *
 * @psalm-type SerializedDomainEvent = array{
 *   event_name: string,
 *   aggregate_id: string,
 *   event_payload: array,
 *   occurred_at: string,
 *   meta: array{
 *      event_id: string,
 *      event_version: int,
 *      domain_trace: array{correlation_id: string, causation_id: string}
 *   }
 *  }
 */
abstract readonly class DomainEvent
{
    public string $eventName;

    /**
     * @param I $aggregateId
     */
    protected function __construct(
        public mixed $aggregateId,
        public DateTimeRFC $occurredAt,
        public DomainEventMeta $meta,
    ) {
        $this->eventName = strtolower(
            preg_replace(
                '/(?<!^)[A-Z]/',
                '_$0',
                (new \ReflectionClass($this))->getShortName(),
            ),
        );
    }

    /** @return SerializedDomainEvent */
    public function serialize(): array
    {
        return [
            'event_name' => $this->eventName,
            'aggregate_id' => $this->aggregateId->value(),
            'event_payload' => $this->serializeEventPayload(),
            'occurred_at' => $this->occurredAt->value(),
            'meta' => [
                'event_id' => $this->meta->eventId->value(),
                'event_version' => $this->meta->version->v,
                'domain_trace' => [
                    'correlation_id' => $this->meta->domainTrace->correlationId->value(),
                    'causation_id' => $this->meta->domainTrace->causationId->value(),
                ],
            ],
        ];
    }

    protected static function deserializeMeta(array $meta): DomainEventMeta
    {
        /** @var string[] $domainTrace */
        $domainTrace = $meta['domain_trace'];

        return new DomainEventMeta(
            EventId::from((string) $meta['event_id']),
            DomainTrace::fromIds(
                CorrelationId::from($domainTrace['correlation_id']),
                CausationId::from($domainTrace['causation_id']),
            ),
            new DomainEventVersion((int) $meta['event_version']),
        );
    }

    abstract protected function serializeEventPayload(): array;

    abstract public function enrich(EnrichOptions $enrichOptions): self;

    protected function enrichedDomainEventMeta(EnrichOptions $enrichOptions): DomainEventMeta
    {
        return new DomainEventMeta(
            $enrichOptions->eventId ?? $this->meta->eventId,
            $enrichOptions->domainTrace,
            $this->meta->version,
        );
    }
}
