<?php

namespace DDDStarterPack\Infrastructure\Application\Notification\Amazon;

use DDDStarterPack\Application\Notification\MessageProducer;
use DDDStarterPack\Application\Notification\MessageProducerResponse;

class AmazonSqsMessageProducer implements MessageProducer
{
    const BATCH_LIMIT = 10;

    /**
     * @var SqsClient
     */
    private $client;

    public function open(string $exchangeName)
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

    public function send(
        string $exchangeName,
        string $notificationMessage,
        string $notificationType,
        int $notificationId,
        \DateTimeInterface $notificationOccurredOn
    ): MessageProducerResponse
    {
        $queueUrl = getenv('AMAZON_SQS_QUEUE_URL');

        $result = $this->client->sendMessage([
            'QueueUrl' => $queueUrl,
            'MessageBody' => $notificationMessage,
        ]);
    }

    public function close($exchangeName)
    {

    }

    public function sendBatch(array $messages): MessageProducerResponse
    {

    }
}
