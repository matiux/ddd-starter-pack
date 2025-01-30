<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\RabbitMQ;

use DDDStarterPack\Message\MessageProducerResponse;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQMessageProducerResponse implements MessageProducerResponse
{
    private $sentMessages;
    private $originalProducedMessage;

    public function __construct(int $sentMessages, null|AMQPMessage $originalProducedMessage = null)
    {
        $this->sentMessages = $sentMessages;
        $this->originalProducedMessage = $originalProducedMessage;
    }

    public function sentMessages(): int
    {
        return $this->sentMessages;
    }

    public function originalResponse(): mixed
    {
        return $this->originalProducedMessage;
    }

    public function body(): mixed
    {
        if ($this->originalProducedMessage) {
            return $this->originalProducedMessage->body;
        }

        return null;
    }

    public function sentMessageId(): mixed
    {
        return null;
    }

    public function isSuccess(): bool
    {
        // TODO: Implement isSuccess() method.
    }
}
