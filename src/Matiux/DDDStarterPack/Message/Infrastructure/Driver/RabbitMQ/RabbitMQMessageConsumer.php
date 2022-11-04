<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Driver\RabbitMQ;

use ArrayObject;
use DDDStarterPack\Message\Infrastructure\Message;
use DDDStarterPack\Message\Infrastructure\MessageConsumer;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQMessageConsumer extends RabbitMQMessanger implements MessageConsumer
{
    private $messageFactory;

    public function __construct(RabbitMQConnection $connectionData, string $queueName, RabbitMQMessageFactory $messageFactory)
    {
        parent::__construct($connectionData, $queueName);

        $this->messageFactory = $messageFactory;
    }

    public function consume(null|string $queue = null): null|Message
    {
        $this->open();

        $body = null;
        $deliveryTag = null;

        $callback = function (AMQPMessage $msg) use (&$body, &$deliveryTag) {
            $body = $msg->getBody();
            $deliveryTag = $msg->delivery_info['delivery_tag'];
            /**
             * Avendo messo a `false` il 4° parametro del metodo `basic_consume`, indichiamo esplicitamente alla coda quando un task è terminato.
             * Se non viene inviata nessuna conferma il job viene rimesso automaticamente in coda e reinviato a un nuovo worker (quando ce n'è uno disponibile).
             * Inviando la conferma il messaggio verrà definitivamente cancellato dalla coda. No conferma = no cancellazione dalla coda e reinvio
             * a un nuovo worker.
             */
            // $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $this->channel->basic_qos(null, 1, null);

        /**
         * Il 4° parametro è per il `message acknowledgments`.
         */
        $this->channel->basic_consume($this->queueName, '', false, false, false, false, $callback);

        while (count($this->channel->callbacks)) {
            if ($body) {
                return $this->messageFactory->build($body, '', $deliveryTag, '', null, null);
                // return new ArrayObject(['Body' => $body, 'ReceiptHandle' => $deliveryTag]);
            }

            try {
                $this->channel->wait(null, true, 1);
            } catch (AMQPTimeoutException $e) {
                $this->close();

                break;
            }
        }

        $this->close();

        return null;
    }

    public function delete(string $messageId, null|string $queue = null): void
    {
        $this->channel->basic_ack($messageId);
    }

    public function deleteBatch(\ArrayObject $messagesId): void
    {
    }

    public function consumeBatch(): array
    {
    }
}
