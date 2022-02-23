<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Driver\RabbitMQ;

use DDDStarterPack\Message\Infrastructure\Message;
use DDDStarterPack\Message\Infrastructure\MessageProducer;
use DDDStarterPack\Message\Infrastructure\MessageProducerResponse;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQMessageProducer extends RabbitMQMessanger implements MessageProducer
{
    public function send($message): MessageProducerResponse
    {
        $body = $message->body();

        $msg = new AMQPMessage($body, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

        try {
            $this->channel->basic_publish($msg, $message->exchangeName(), $this->queueName);

            return new RabbitMQMessageProducerResponse(1, $msg);
        } catch (\Exception $exception) {
            /**
             * TODO.
             */
            throw $exception;
        }
    }

    /**
     * @param Message[] $messages
     *
     * @return MessageProducerResponse
     */
    public function sendBatch(array $messages): MessageProducerResponse
    {
    }

    public function getBatchLimit(): int
    {
    }
}
