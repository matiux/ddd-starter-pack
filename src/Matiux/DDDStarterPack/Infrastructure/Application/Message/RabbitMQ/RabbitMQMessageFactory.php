<?php

namespace DDDStarterPack\Infrastructure\Application\Message\RabbitMQ;

use DateTimeImmutable;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageFactory;

class RabbitMQMessageFactory implements MessageFactory
{
    public function build(string $body, string $exchangeName = '', DateTimeImmutable $occurredAt = null, string $type = '', $id = null): Message
    {
        return new RabbitMQMessage($body, $exchangeName, $occurredAt, $type, $id);
    }
}
