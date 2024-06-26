<?php

declare(strict_types=1);

namespace DDDStarterPack\Message;

interface MessageConsumer extends MessageService
{
    public function consume(null|string $queue = null): null|Message;

    /**
     * @return Message[]
     */
    public function consumeBatch(null|string $queue = null, int $maxNumberOfMessages = 1): array;

    public function delete(string $messageId, null|string $queue = null): void;

    public function deleteBatch(\ArrayObject $messagesId): void;
}
