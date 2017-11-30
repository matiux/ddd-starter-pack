<?php

namespace DddStarterPack\Infrastructure\Application\Notification\Amazon;

use DddStarterPack\Application\Notification\MessageProducer;

class AmazonSqsMessageProducer implements MessageProducer
{
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
    )
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
}
