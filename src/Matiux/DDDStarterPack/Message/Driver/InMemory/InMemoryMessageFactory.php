<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\InMemory;

use DDDStarterPack\Message\MessageFactory;

/**
 * @implements MessageFactory<InMemoryMessage>
 */
class InMemoryMessageFactory implements MessageFactory
{
    #[\Override]
    public function build(
        string $body,
        \DateTimeImmutable|null $occurredAt = null,
        string|null $type = null,
        string|null $id = null,
        array $extra = [],
        string|null $exchangeName = null,
    ) {
        return new InMemoryMessage(
            body: $body,
            occurredAt: $occurredAt,
            type: $type,
            id: $id,
            extra: $extra,
        );
    }
}
