<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

interface MessageProducer extends MessageService
{
    public function send(Message $message): MessageProducerResponse;

    /**
     * @param Message[] $messages
     *
     * @return MessageProducerResponse
     */
    public function sendBatch(array $messages): MessageProducerResponse;

    public function getBatchLimit(): int;
}
