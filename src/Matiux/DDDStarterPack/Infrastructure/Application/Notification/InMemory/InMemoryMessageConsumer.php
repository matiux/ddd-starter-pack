<?php

namespace DDDStarterPack\Infrastructure\Application\Notification\InMemory;

use DDDStarterPack\Application\Notification\MessageConsumer;

class InMemoryMessageConsumer implements MessageConsumer
{
    private $messageQueue;

    public function __construct(InMemoryMessageQueue $messageQueue)
    {
        $this->messageQueue = $messageQueue;
    }

    public function open(string $exchangeName)
    {

    }

    /**
     * TODO Pensare a un formato più agnostico
     * @param int $maximumNumberOfMessages
     * @return array
     */
    public function receiveMessage(int $maximumNumberOfMessages = 1)
    {
        $messageOrig = $this->messageQueue->popMessage();

        $message['Body'] = $messageOrig;

        $messageOrig = json_decode($messageOrig, true);
        $message['MessageId'] = $messageOrig['event_id'];
        $message['ReceiptHandle'] = $messageOrig['event_id'];
        $message['MD5OfBody'] = '...';

        return [$message];
    }

    public function close($exchangeName)
    {

    }

    public function deleteMessage($messageId)
    {

    }

    public function deleteMessageBatch(array $messagesId)
    {

    }
}
