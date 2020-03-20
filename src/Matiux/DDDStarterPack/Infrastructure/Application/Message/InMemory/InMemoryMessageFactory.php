<?php

namespace DDDStarterPack\Infrastructure\Application\Message\InMemory;

use DateTimeImmutable;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageFactory;

class InMemoryMessageFactory implements MessageFactory
{

    public function build(string $body, string $exchangeName = '', DateTimeImmutable $occurredAt = null, string $type = '', $id = null): Message
    {
        return new InMemoryMessage($exchangeName, $id, $body, $type, $occurredAt);
    }
}
