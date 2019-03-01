<?php

namespace DDDStarterPack\Infrastructure\Application\Message\Amazon;

use ArrayObject;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageProducer;
use DDDStarterPack\Application\Message\MessageProducerResponse;

class AmazonSqsMessageProducer implements MessageProducer
{
    const BATCH_LIMIT = 10;

    /**
     * @var SqsClient
     */
    private $client;

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

    public function send(Message $message): MessageProducerResponse
    {
        $queueUrl = getenv('AMAZON_SQS_QUEUE_URL');

        $result = $this->client->sendMessage([
            'QueueUrl' => $queueUrl,
            'MessageBody' => $message->getNotificationBodyMessage(),
        ]);
    }

    public function close(string $exchangeName = ''): void
    {

    }

    public function sendBatch(ArrayObject $messages): MessageProducerResponse
    {

    }

    public function getBatchLimit(): int
    {

    }
}
