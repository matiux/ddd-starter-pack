<?php

namespace DDDStarterPack\Infrastructure\Application\Message\SQS;

use DateTimeImmutable;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageFactory;

class SQSMessageFactory implements MessageFactory
{
    public function build(string $body, string $exchangeName = null, DateTimeImmutable $occurredAt = null, string $type = null, $id = null): Message
    {
        return new SQSMessage($body, $occurredAt, $type, $id);
    }
}
