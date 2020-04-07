<?php

namespace DDDStarterPack\Infrastructure\Application\Message\Sqs;

use ArrayObject;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageProducer;
use DDDStarterPack\Application\Message\MessageProducerResponse;

class SqsMessageProducer implements MessageProducer
{
    const BATCH_LIMIT = 1;

    /** @var SqsClient */
    private $client;

    public function send(Message $message): MessageProducerResponse
    {
        $queueUrl = getenv('AMAZON_SQS_QUEUE_URL');

        $result = $this->client->sendMessage([
            'QueueUrl' => $queueUrl,
            'MessageBody' => $message->getNotificationBodyMessage(),
        ]);
    }

    public function sendBatch(ArrayObject $messages): MessageProducerResponse
    {
        // TODO: Implement sendBatch() method.
    }

    public function getBatchLimit(): int
    {
        // TODO: Implement getBatchLimit() method.
    }

    public function open(string $exchangeName = ''): void
    {
        $this->client = new SqsClient([
            'version' => 'latest',
            'region' => 'us-east-1',
            'credentials' => [
                'key' => getenv('AMAZON_SQS_KEY'),
                'secret' => getenv('AMAZON_SQS_SECRET'),
            ],
        ]);
    }

    public function close(string $exchangeName = ''): void
    {
        // TODO: Implement close() method.
    }
}
