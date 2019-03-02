<?php

namespace DDDStarterPack\Application\Message;

use DateTimeImmutable;

interface MessageFactory
{
    public function build(string $body, string $exchangeName = '', DateTimeImmutable $occurredAt = null, string $type = '', $id = null): Message;
}
