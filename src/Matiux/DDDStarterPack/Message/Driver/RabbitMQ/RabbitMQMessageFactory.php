<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\RabbitMQ;

use DDDStarterPack\Message\MessageFactory;

/**
 * @implements MessageFactory<RabbitMQMessage>
 */
class RabbitMQMessageFactory implements MessageFactory
{
    public function build(
        string $body,
        \DateTimeImmutable|null $occurredAt = null,
        string|null $type = null,
        string|null $id = null,
        array $extra = [],
        string|null $exchangeName = null,
    ) {
        return new RabbitMQMessage(
            body: $body,
            occurredAt: $occurredAt,
            type: $type,
            id: $id,
            extra: $extra,
        );
    }
}
