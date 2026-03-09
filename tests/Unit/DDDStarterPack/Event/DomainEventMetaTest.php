<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Event;

use DDDStarterPack\Event\DomainEventMeta;
use DDDStarterPack\Event\DomainEventVersion;
use DDDStarterPack\Event\EventId;
use DDDStarterPack\Identity\Trace\DomainTrace;
use PHPUnit\Framework\TestCase;

class DomainEventMetaTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_serialize(): void
    {
        $eventId = EventId::new();
        $domainTrace = DomainTrace::init($eventId);
        $v = new DomainEventVersion(1);

        $meta = new DomainEventMeta($eventId, $domainTrace, $v);

        $expected = [
            'event_id' => $eventId->value(),
            'event_version' => 1,
            'context' => null,
            'domain_trace' => [
                'correlation_id' => $eventId->value(),
                'causation_id' => null,
            ],
        ];

        $encoded = $meta->serialize();

        self::assertEquals($expected, $encoded);
        self::assertNull($meta->context());
    }

    /**
     * @test
     */
    public function it_should_deserialize(): void
    {
        $eventId = EventId::new();
        $serialized = [
            'event_id' => $eventId->value(),
            'event_version' => 1,
            'context' => null,
            'domain_trace' => [
                'correlation_id' => $eventId->value(),
                'causation_id' => $eventId->value(),
            ],
        ];

        $meta = DomainEventMeta::deserialize($serialized);

        self::assertSame($serialized, $meta->serialize());
        self::assertNull($meta->context());
    }
}
