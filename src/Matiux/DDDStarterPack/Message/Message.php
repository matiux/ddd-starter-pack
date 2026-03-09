<?php

declare(strict_types=1);

namespace DDDStarterPack\Message;

interface Message
{
    public function exchangeName(): string|null;

    public function body(): string;

    public function type(): string|null;

    public function id(): mixed;

    public function occurredAt(): \DateTimeImmutable|null;

    public function extra(): array;
}
