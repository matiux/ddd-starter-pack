<?php

namespace DDDStarterPack\Application\Notification;

interface MessageProducer
{
    public function open(string $exchangeName);

    public function send(Message $message): MessageProducerResponse;

    /**
     * @param \ArrayObject|Message[] $messages
     * @return MessageProducerResponse
     */
    public function sendBatch(\ArrayObject $messages): MessageProducerResponse;

    public function getBatchLimit(): int;

    public function close($exchangeName);
}
