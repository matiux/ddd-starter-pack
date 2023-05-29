<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Event;

use DDDStarterPack\Event\DomainEvent;
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
    public function it_shoud_serialize_event(): void
    {
        $eventId = EventId::new();
        $aggregateId = AggregateId::new();
        $domainTrace = DomainTrace::init($eventId);
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
        self::assertArrayHasKey('domain_trace', $serialized);

        self::assertEquals($expectedEventPayload, $serialized['event_payload']);
        self::assertEquals($expectedDomainTrace, $serialized['domain_trace']);
        self::assertEquals('something_happened', $event->eventName);
    }
}

/**
 * @extends DomainEvent<AggregateId>
 */
readonly class SomethingHappened extends DomainEvent
{
    protected function __construct(
        EventId $eventId,
        AggregateId $aggregateId,
        DomainTrace $domainTrace,
        DateTimeRFC $occurredAt,
        public string $name,
    ) {
        parent::__construct(
            $eventId,
            $aggregateId,
            $domainTrace,
            new DomainEventVersion(1),
            $occurredAt,
        );
    }

    public static function crea(AggregateId $aggregateId, DateTimeRFC $occurredAt, string $name): self
    {
        $eventId = EventId::new();

        return new self(
            $eventId,
            $aggregateId,
            DomainTrace::init($eventId),
            $occurredAt,
            $name,
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
            $this->eventId,
            $this->aggregateId,
            $enrichOptions->domainTrace,
            $this->occurredAt,
            $this->name,
        );
    }
}
