<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\InMemory;

use DateTimeImmutable;
use DDDStarterPack\Message\Application\MessageFactory;

/**
 * @implements MessageFactory<InMemoryMessage>
 */
class InMemoryMessageFactory implements MessageFactory
{
    public function build(
        string $body,
        null|DateTimeImmutable $occurredAt = null,
        null|string $type = null,
        null|string $id = null,
        array $extra = [],
        null|string $exchangeName = null
    ) {
        return new InMemoryMessage(
            body: $body,
            occurredAt: $occurredAt,
            type: $type,
            id: $id
        );
    }
}
