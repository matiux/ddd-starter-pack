<?php

namespace DDDStarterPack\Application\Message;

use ArrayObject;

interface MessageConsumer extends MessageService
{
    public function consume(): ?Message;

    /**
     * @return Message[]
     */
    public function consumeBatch(): array;

    public function delete($messageId): void;

    public function deleteBatch(ArrayObject $messagesId): void;
}
