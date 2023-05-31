<?php

declare(strict_types=1);

namespace DDDStarterPack\Event\Test;

trait EventTestUtil
{
    public static function assertIsDomainEventSerialized(array $serialized): void
    {
        self::assertArrayHasKey('aggregate_id', $serialized);
        self::assertArrayHasKey('occurred_at', $serialized);
        self::assertArrayHasKey('event_payload', $serialized);

        self::assertArrayHasKey('meta', $serialized);

        $meta = $serialized['meta'];

        self::assertArrayHasKey('event_id', $meta);
        self::assertArrayHasKey('event_version', $meta);
        self::assertArrayHasKey('domain_trace', $meta);
    }
}
