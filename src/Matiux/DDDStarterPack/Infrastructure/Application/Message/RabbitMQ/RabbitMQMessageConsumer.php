<?php

namespace DDDStarterPack\Infrastructure\Application\Message\RabbitMQ;

use ArrayObject;
use DDDStarterPack\Application\Message\MessageConsumer;

class RabbitMQMessageConsumer extends RabbitMQMessanger implements MessageConsumer
{
    public function receiveMessage(int $maximumNumberOfMessages = 1): ArrayObject
    {
        $body = null;
        $deliveryTag = null;

        $callback = function (AMQPMessage $msg) use (&$body, &$deliveryTag) {

            $body = $msg->getBody();

            $deliveryTag = $msg->delivery_info['delivery_tag'];
            /**
             * Avendo messo a `false` il 4° parametro del metodo `basic_consume`, indichiamo esplicitamente alla coda quando un task è terminato.
             * Se non viene inviata nessuna conferma il job viene rimesso automaticamente in coda e reinviato a un nuovo worker (quando ce n'è uno disponibile).
             * Inviando la conferma il messaggio verrà definitivamente cancellato dalla coda. No conferma = no cancellazione dalla coda e reinvio
             * a un nuovo worker
             */
            //$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $this->channel->basic_qos(null, 1, null);

        /**
         * Il 4° parametro è per il `message acknowledgments`
         */
        $this->channel->basic_consume($this->queueName, '', false, false, false, false, $callback);

        while (count($this->channel->callbacks)) {

            if ($body) {

                return new ArrayObject(['Body' => $body, 'ReceiptHandle' => $deliveryTag]);
            }

            try {

                $this->channel->wait(null, true, 1);

            } catch (AMQPTimeoutException $e) {

                $this->close('');
                break;
            }
        }

        $this->close();
    }

    public function deleteMessage($messageId): void
    {
        $this->channel->basic_ack($messageId);
    }

    public function deleteMessageBatch(ArrayObject $messagesId): void
    {

    }
}
