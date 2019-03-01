<?php

namespace DDDStarterPack\Application\Message;

use DateTimeInterface;

interface Message
{
    public function exchangeName(): string;

    public function body(): string;

    public function type(): string;

    public function id();

    public function occurredAt(): DateTimeInterface;
}
