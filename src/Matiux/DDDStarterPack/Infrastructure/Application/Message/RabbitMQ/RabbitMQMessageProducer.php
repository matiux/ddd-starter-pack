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
        $body = $message->getNotificationBodyMessage();

        $msg = new AMQPMessage($body, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

        $this->channel->basic_publish($msg, '', $this->queueName);

        return new RabbitMQMessageProducerResponse(1);
    }

    public function sendBatch(ArrayObject $messages): MessageProducerResponse
    {

    }

    public function getBatchLimit(): int
    {

    }

    public function open(string $exchangeName = ''): void
    {

    }

    public function close(string $exchangeName = ''): void
    {

    }
}
