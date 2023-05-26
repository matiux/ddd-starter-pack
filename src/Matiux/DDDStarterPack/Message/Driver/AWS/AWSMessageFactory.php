<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS;

use DDDStarterPack\Message\MessageFactory;

/**
 * @implements MessageFactory<AWSMessage>
 */
class AWSMessageFactory implements MessageFactory
{
    public function build(
        string $body,
        null|\DateTimeImmutable $occurredAt = null,
        null|string $type = null,
        null|string $id = null,
        array $extra = [],
        null|string $exchangeName = null,
    ) {
        return new AWSMessage(
            body: $body,
            occurredAt: $occurredAt,
            type: $type,
            id: $id,
            extra: $extra,
        );
    }
}
