<?php

namespace DDDStarterPack\Infrastructure\Application\Message\RabbitMQ;

use ArrayObject;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageProducer;
use DDDStarterPack\Application\Message\MessageProducerResponse;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQMessageProducer extends RabbitMQMessanger implements MessageProducer
{
    public function send(Message $message): MessageProducerResponse
    {
        $body = $message->body();

        $msg = new AMQPMessage($body, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

        try {

            $this->channel->basic_publish($msg, $message->exchangeName(), $this->queueName);

            return new RabbitMQMessageProducerResponse(1, $msg);

        } catch (\Exception $exception) {

            /**
             * TODO
             */
            throw $exception;
        }
    }

    public function sendBatch(ArrayObject $messages): MessageProducerResponse
    {

    }

    public function getBatchLimit(): int
    {

    }
}
