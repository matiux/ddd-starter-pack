<?php

declare(strict_types=1);

namespace DDDStarterPack\Message;

interface MessageConsumer extends MessageService
{
    public function consume(string|null $queue = null): Message|null;

    /**
     * @return Message[]
     */
    public function consumeBatch(string|null $queue = null, int $maxNumberOfMessages = 1): array;

    public function delete(string $messageId, string|null $queue = null): void;

    public function deleteBatch(\ArrayObject $messagesId): void;
}
