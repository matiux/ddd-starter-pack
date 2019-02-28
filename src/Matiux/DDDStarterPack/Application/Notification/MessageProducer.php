<?php

namespace DDDStarterPack\Application\Notification;

use ArrayObject;

interface MessageProducer extends MessageService
{
    public function send(Message $message): MessageProducerResponse;

    /**
     * @param ArrayObject|Message[] $messages
     * @return MessageProducerResponse
     */
    public function sendBatch(ArrayObject $messages): MessageProducerResponse;

    public function getBatchLimit(): int;
}
