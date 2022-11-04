<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Driver\InMemory;

use DDDStarterPack\Message\Infrastructure\MessageFactory;

/**
 * @implements MessageFactory<InMemoryMessage>
 */
class InMemoryMessageFactory implements MessageFactory
{
    public function build(
        string $body,
        null|\DateTimeImmutable $occurredAt = null,
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
