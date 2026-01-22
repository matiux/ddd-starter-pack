<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS;

use DDDStarterPack\Message\MessageFactory;

/**
 * @implements MessageFactory<AWSMessage>
 */
class AWSMessageFactory implements MessageFactory
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
        return new AWSMessage(
            body: $body,
            occurredAt: $occurredAt,
            type: $type,
            id: $id,
            extra: $extra,
        );
    }
}
