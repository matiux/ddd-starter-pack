<?php

namespace DDDStarterPack\Infrastructure\Application\Message\InMemory;

use ArrayObject;
use DDDStarterPack\Application\Message\MessageConsumer;

class InMemoryMessageConsumer implements MessageConsumer
{
    private $messageQueue;

    public function __construct(InMemoryMessageQueue $messageQueue)
    {
        $this->messageQueue = $messageQueue;
    }

    public function receiveMessage(int $maximumNumberOfMessages = 1): ArrayObject
    {
        $messageOrig = $this->messageQueue->popMessage();

        $message['Body'] = $messageOrig;

        $messageOrig = json_decode($messageOrig, true);
        $message['MessageId'] = $messageOrig['event_id'];
        $message['ReceiptHandle'] = $messageOrig['event_id'];
        $message['MD5OfBody'] = '...';

        return [$message];
    }

    public function deleteMessage($messageId): void
    {

    }

    public function deleteMessageBatch(ArrayObject $messagesId): void
    {

    }

    public function open(string $exchangeName = ''): void
    {

    }

    public function close(string $exchangeName = ''): void
    {

    }
}
