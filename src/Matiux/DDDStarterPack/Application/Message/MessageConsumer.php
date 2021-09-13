<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

use ArrayObject;

interface MessageConsumer extends MessageService
{
    public function consume(null|string $queue = null): null|Message;

    /**
     * @return Message[]
     */
    public function consumeBatch(): array;

    public function delete(string $messageId, null|string $queue = null): void;

    public function deleteBatch(ArrayObject $messagesId): void;
}
