<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Application;

use DDDStarterPack\Message\Application\Exception\MessageInvalidException;

/**
 * @template T
 * @extends MessageService
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
