<?php

namespace DDDStarterPack\Application\Message;

use ArrayObject;

interface MessageConsumer extends MessageService
{
    public function receiveMessage(): ?Message;

    public function deleteMessage($messageId): void;

    public function deleteMessageBatch(ArrayObject $messagesId): void;
}
