<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

use ArrayObject;

interface MessageConsumer extends MessageService
{
    public function consume(): ?Message;

    /**
     * @return Message[]
     */
    public function consumeBatch(): array;

    public function delete(string $messageId): void;

    public function deleteBatch(ArrayObject $messagesId): void;
}
