<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Application;

use DateTimeImmutable;

interface Message
{
    public function exchangeName(): null|string;

    public function body(): string;

    public function type(): null|string;

    public function id(): mixed;

    public function occurredAt(): DateTimeImmutable|null;
}
