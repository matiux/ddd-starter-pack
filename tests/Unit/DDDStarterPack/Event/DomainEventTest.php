<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Event;

use DDDStarterPack\Event\DomainEvent;
use DDDStarterPack\Event\EventId;
use DDDStarterPack\Identity\AggregateId;
use DDDStarterPack\Identity\Trace\DomainTrace;
use DDDStarterPack\Type\DateTimeRFC;
use PHPUnit\Framework\TestCase;

class DomainEventTest extends TestCase
{
    /**
     * @test
     */
    public function name(): void
    {
        $eventId = EventId::new();
        $aggregateId = AggregateId::new();
        $domainTrace = DomainTrace::init($eventId);
        $occurredAt = new DateTimeRFC();

        $event = new SomethingHappened(
            $eventId,
            $aggregateId,
            $domainTrace,
            $occurredAt,
            'Matiux',
        );

        $expectedSerializedEvent = [
            'event_id' => $eventId->value(),
            'aggregate_id' => $aggregateId->value(),
            'event_data' => [
                'name' => 'Matiux',
            ],
            'domain_trace' => [
                'correlation_id' => $domainTrace->correlationId,
                'causation_id' => $domainTrace->causationId,
            ],
            'occurred_at' => $occurredAt->value(),
        ];

        self::assertEquals($expectedSerializedEvent, $event->serialize());
    }
}

readonly class SomethingHappened extends DomainEvent
{
    public function __construct(
        EventId $eventId,
        AggregateId $aggregateId,
        DomainTrace $domainTrace,
        DateTimeRFC $occurredAt,
        private string $name,
    ) {
        parent::__construct($eventId, $aggregateId, $domainTrace, $occurredAt);
    }

    protected function serializeEventData(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
