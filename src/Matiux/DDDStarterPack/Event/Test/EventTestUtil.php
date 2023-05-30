<?php

declare(strict_types=1);

namespace DDDStarterPack\Event\Test;

trait EventTestUtil
{
    public static function assertIsDomainEventSerialized(array $serialized): void
    {
        self::assertArrayHasKey('event_id', $serialized);
        self::assertArrayHasKey('occurred_at', $serialized);
        self::assertArrayHasKey('aggregate_id', $serialized);
        self::assertArrayHasKey('event_payload', $serialized);
        self::assertArrayHasKey('event_version', $serialized);
        self::assertArrayHasKey('domain_trace', $serialized);
    }
}
