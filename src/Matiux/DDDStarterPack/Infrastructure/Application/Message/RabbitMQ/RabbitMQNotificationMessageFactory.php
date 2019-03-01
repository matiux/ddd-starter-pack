<?php

namespace DDDStarterPack\Infrastructure\Application\Message\RabbitMQ;


use DateTimeInterface;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageFactory;

class RabbitMQNotificationMessageFactory implements MessageFactory
{
    public function build(string $exchangeName, $messageId, string $body, string $type, DateTimeInterface $occurredAt): Message
    {
        return new RabbitMQMessage($exchangeName, $messageId, $body, $type, $occurredAt);
    }
}
