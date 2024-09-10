<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Event;

use DDDStarterPack\Event\DomainEvent;
use DDDStarterPack\Event\DomainEventMeta;
use DDDStarterPack\Event\DomainEventVersion;
use DDDStarterPack\Event\EnrichOptions;
use DDDStarterPack\Event\EventId;
use DDDStarterPack\Identity\AggregateId;
use DDDStarterPack\Identity\Trace\CausationId;
use DDDStarterPack\Identity\Trace\CorrelationId;
use DDDStarterPack\Identity\Trace\DomainTrace;
use DDDStarterPack\Type\DateTimeRFC;
use PHPUnit\Framework\TestCase;

class DomainEventTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_serialize_event(): void
    {
        $aggregateId = AggregateId::new();
        $occurredAt = new DateTimeRFC();

        $event = SomethingHappened::crea(
            $aggregateId,
            $occurredAt,
            'Matiux',
        );

        $event = $event->enrich(
            new EnrichOptions(
                DomainTrace::fromIds($correlationId = CorrelationId::new(), $causationId = CausationId::new()),
            ),
        );

        $expectedEventPayload = [
            'name' => 'Matiux',
        ];

        $expectedDomainTrace = [
            'correlation_id' => $correlationId->value(),
            'causation_id' => $causationId->value(),
        ];

        $serialized = $event->serialize();
        self::assertArrayHasKey('event_payload', $serialized);
        self::assertArrayHasKey('meta', $serialized);

        $meta = $serialized['meta'];

        self::assertEquals($expectedEventPayload, $serialized['event_payload']);
        self::assertEquals($expectedDomainTrace, $meta['domain_trace']);
        self::assertEquals('something_happened', $event->eventName);
    }

    /**
     * @test
     */
    public function it_should_deserialize(): void
    {
        $aggregateId = AggregateId::new();
        $occurredAt = new DateTimeRFC();

        $serializedEvent = SomethingHappened::crea(
            $aggregateId,
            $occurredAt,
            'Matiux',
        )->serialize();

        $event = SomethingHappened::deserialize($serializedEvent);

        self::assertTrue($event->aggregateId->equals($aggregateId));
        self::assertEquals($occurredAt, $event->occurredAt);
    }

    /**
     * @test
     */
    public function it_should_remove_version_if_present(): void
    {
        $aggregateId = AggregateId::new();
        $occurredAt = new DateTimeRFC();

        $serializedEvent = SomethingHappenedV2::crea($aggregateId, $occurredAt)->serialize();

        self::assertEquals('something_happened', $serializedEvent['event_name']);
    }
}

/**
 * @psalm-import-type SerializedDomainEvent from DomainEvent
 *
 * @extends DomainEvent<AggregateId>
 */
readonly class SomethingHappened extends DomainEvent
{
    protected function __construct(
        AggregateId $aggregateId,
        DateTimeRFC $occurredAt,
        DomainEventMeta $domainEventMeta,
        public string $name,
    ) {
        parent::__construct($aggregateId, $occurredAt, $domainEventMeta);
    }

    public static function crea(AggregateId $aggregateId, DateTimeRFC $occurredAt, string $name): self
    {
        $eventId = EventId::new();

        return new self(
            $aggregateId,
            $occurredAt,
            new DomainEventMeta($eventId, DomainTrace::init($eventId), new DomainEventVersion(1)),
            $name,
        );
    }

    /**
     * @param SerializedDomainEvent $data
     *
     * @throws \Exception
     */
    public static function deserialize(array $data): self
    {
        return new self(
            AggregateId::from($data['aggregate_id']),
            DateTimeRFC::from($data['occurred_at']),
            self::deserializeMeta($data['meta']),
            (string) $data['event_payload']['name'],
        );
    }

    protected function serializeEventPayload(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    public function enrich(EnrichOptions $enrichOptions): self
    {
        return new self(
            $this->aggregateId,
            $this->occurredAt,
            $this->enrichedDomainEventMeta($enrichOptions),
            $this->name,
        );
    }
}

/**
 * @psalm-import-type SerializedDomainEvent from DomainEvent
 *
 * @extends DomainEvent<AggregateId>
 */
readonly class SomethingHappenedV2 extends DomainEvent
{
    public static function crea(AggregateId $aggregateId, DateTimeRFC $occurredAt): self
    {
        $eventId = EventId::new();

        return new self(
            $aggregateId,
            $occurredAt,
            new DomainEventMeta($eventId, DomainTrace::init($eventId), new DomainEventVersion(2)),
        );
    }

    /**
     * @param SerializedDomainEvent $data
     *
     * @throws \Exception
     */
    public static function deserialize(array $data): self
    {
        return new self(
            AggregateId::from($data['aggregate_id']),
            DateTimeRFC::from($data['occurred_at']),
            self::deserializeMeta($data['meta']),
        );
    }

    protected function serializeEventPayload(): array
    {
        return [];
    }

    public function enrich(EnrichOptions $enrichOptions): self
    {
        return new self(
            $this->aggregateId,
            $this->occurredAt,
            $this->enrichedDomainEventMeta($enrichOptions),
        );
    }
}
