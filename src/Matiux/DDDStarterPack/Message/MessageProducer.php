<?php

declare(strict_types=1);

namespace DDDStarterPack\Message;

use DDDStarterPack\Message\Exception\MessageInvalidException;

/**
 * @template T
 */
interface MessageProducer extends MessageService
{
    /**
     * @param T $message
     *
     * @throws MessageInvalidException
     *
     * @return MessageProducerResponse
     */
    public function send($message): MessageProducerResponse;

    /**
     * @param T[] $messages
     *
     * @return MessageProducerResponse
     */
    public function sendBatch(array $messages): MessageProducerResponse;

    public function getBatchLimit(): int;
}
