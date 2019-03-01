<?php

namespace DDDStarterPack\Infrastructure\Application\Message\InMemory;

use DateTimeInterface;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageFactory;

class InMemoryMessageFactory implements MessageFactory
{

    public function build(string $exchangeName, $messageId, string $body, string $type, DateTimeInterface $occurredAt): Message
    {
        return new InMemoryMessage($exchangeName, $messageId, $body, $type, $occurredAt);
    }
}
