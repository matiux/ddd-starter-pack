<?php

namespace DDDStarterPack\Application\Notification;

interface MessageConsumer extends MessageService
{
    public function receiveMessage(int $maximumNumberOfMessages = 1): array;

    public function deleteMessage($messageId): void;

    public function deleteMessageBatch(array $messagesId): void;
}
