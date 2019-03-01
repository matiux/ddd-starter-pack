<?php

namespace DDDStarterPack\Application\Message;

use ArrayObject;

interface MessageConsumer extends MessageService
{
    public function receiveMessage(int $maximumNumberOfMessages = 1): ArrayObject;

    public function deleteMessage($messageId): void;

    public function deleteMessageBatch(ArrayObject $messagesId): void;
}
