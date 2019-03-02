<?php

namespace DDDStarterPack\Infrastructure\Application\Message\RabbitMQ;

use DDDStarterPack\Application\Message\MessageProducerResponse;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQMessageProducerResponse implements MessageProducerResponse
{
    private $sentMessages;
    private $originalProducedMessage;

    public function __construct(int $sentMessages, AMQPMessage $originalProducedMessage = null)
    {
        $this->sentMessages = $sentMessages;
        $this->originalProducedMessage = $originalProducedMessage;
    }

    public function sentMessages(): int
    {
        return $this->sentMessages;
    }

    public function originalResponse()
    {
        return $this->originalProducedMessage;
    }

    public function body()
    {
        if ($this->originalProducedMessage) {
            return $this->originalProducedMessage->body;
        }

        return null;
    }

    public function sentMessageId()
    {
        return null;
    }
}
