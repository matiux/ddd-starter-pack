<?php

namespace DDDStarterPack\Application\Message;

use DateTimeInterface;

interface MessageFactory
{
    public function build(string $exchangeName, $messageId, string $body, string $type, DateTimeInterface $occurredAt): Message;
}
