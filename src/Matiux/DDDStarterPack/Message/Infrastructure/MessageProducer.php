<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure;

use DDDStarterPack\Message\Infrastructure\Exception\MessageInvalidException;

/**
 * @template T
 *
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
