<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

interface MessageProducerResponse
{
    public function isSuccess(): bool;

    public function sentMessages(): int;

    public function originalResponse(): mixed;

    public function body(): mixed;

    public function sentMessageId(): mixed;
}
