<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Driver\RabbitMQ;

use DateTimeImmutable;
use DDDStarterPack\Message\Infrastructure\MessageFactory;

/**
 * @implements MessageFactory<RabbitMQMessage>
 */
class RabbitMQMessageFactory implements MessageFactory
{
    public function build(
        string $body,
        null|DateTimeImmutable $occurredAt = null,
        null|string $type = null,
        null|string $id = null,
        array $extra = [],
        null|string $exchangeName = null
    ) {
        return new RabbitMQMessage(
            $body,
            $id,
            $id,
            $occurredAt,
            $type
        );
    }
}