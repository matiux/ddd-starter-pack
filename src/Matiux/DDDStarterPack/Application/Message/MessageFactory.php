<?php

namespace DDDStarterPack\Application\Message;

use DateTimeImmutable;

interface MessageFactory
{
    public function build(string $body, string $exchangeName = null, DateTimeImmutable $occurredAt = null, string $type = null, $id = null): Message;
}
