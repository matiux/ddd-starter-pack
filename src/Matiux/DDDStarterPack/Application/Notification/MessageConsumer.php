<?php

namespace DDDStarterPack\Application\Notification;

interface MessageConsumer
{
    public function open(string $exchangeName);

    public function receiveMessage(int $maximumNumberOfMessages = 1);

    public function deleteMessage($messageId);

    public function deleteMessageBatch(array $messagesId);

    public function close($exchangeName);
}
