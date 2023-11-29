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
     * @return array<array-key, array{0: string[], 1: bool}>
     */
    public static function provideExpectedKeys(): array
    {
        return [
            'snake case' => [
                ['event_id', 'correlation_id', 'causation_id', 'event_version', 'context'],
                false,
            ],
            'camel case' => [
                ['eventId', 'correlationId', 'causationId', 'eventVersion', 'context'],
                true,
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideExpectedKeys
     *
     * @param string[] $expectedKeys
     */
    public function it_should_encode(array $expectedKeys, bool $requestCamelCaseEncoding): void
    {
        $eventId = EventId::new();
        $domainTrace = DomainTrace::init($eventId);
        $v = new DomainEventVersion(1);

        $meta = new DomainEventMeta($eventId, $domainTrace, $v);

        $expectedValues = [
            $eventId->value(),
            $eventId->value(),
            $eventId->value(),
            1,
            null,
        ];
        $expected = array_combine($expectedKeys, $expectedValues);

        $encoded = $meta->toArray($requestCamelCaseEncoding);

        self::assertEquals($expected, $encoded);
        self::assertNull($meta->context());
    }
}
