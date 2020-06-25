<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

use DateTimeImmutable;

interface Message
{
    public function exchangeName(): ?string;

    public function body(): string;

    public function type(): ?string;

    /**
     * @return mixed
     */
    public function id();

    public function occurredAt(): ?DateTimeImmutable;
}
