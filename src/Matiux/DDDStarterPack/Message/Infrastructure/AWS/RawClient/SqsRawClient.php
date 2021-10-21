<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\AWS\RawClient;

use Aws\Result;
use Aws\Sqs\SqsClient;
use DDDStarterPack\Util\EnvVarUtil;
use Webmozart\Assert\Assert;

trait SqsRawClient
{
    use AWSCredentials;

    private null|SqsClient $sqsClient = null;
    private null|string $queueUrl = null;

    public function getQueueUrl(): string
    {
        Assert::notNull($this->queueUrl, 'Coda SQS non può essere null');

        return $this->queueUrl;
    }

    protected function getSqsClient(null|string $queueUrl = null): SqsClient
    {
        if ($queueUrl) {
            $this->setQueueUrl($queueUrl);
        }

        if (!$this->sqsClient) {
            $args = [
                'version' => 'latest',
                'region' => EnvVarUtil::get('AWS_DEFAULT_REGION', 'eu-west-1'),
                'debug' => false,
            ];

            $this->sqsClient = new SqsClient($args + $this->createCredentials());
        }

        return $this->sqsClient;
    }

    protected function purgeSqsQueue(null|string $queueUrl = null): void
    {
        if ($queueUrl) {
            $this->setQueueUrl($queueUrl);
        }

        while (true) {
            /** @var list<array{MessageId: string, ReceiptHandle: string, MD5OfBody: string, Body: string }> $messages */
            $messages = $this->pullFromSqsQueue(10)->get('Messages');

            if (empty($message)) {
                break;
            }

            foreach ($messages as $message) {
                $this->getSqsClient()->deleteMessage([
                    'QueueUrl' => $this->getQueueUrl(),
                    'ReceiptHandle' => $message['ReceiptHandle'],
                ]);
            }
        }
    }

    protected function setQueueUrl(string $queueUrl): void
    {
        // ?? EnvVarUtil::get('AWS_SQS_QUEUE_NAME')
        $this->queueUrl = $queueUrl;
    }

    protected function pullFromSqsQueue(int $amountOfMessagesToFetch = 1, null|string $queueUrl = null): Result
    {
        if ($queueUrl) {
            $this->setQueueUrl($queueUrl);
        }

        $options = [
            'QueueUrl' => $this->getQueueUrl(),
            'MaxNumberOfMessages' => $amountOfMessagesToFetch,
            'WaitTimeSeconds' => 2,
        ];

        return $this->getSqsClient()->receiveMessage($options);
    }

    protected function deleteMessage(string $id): void
    {
        $this->getSqsClient()->deleteMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'ReceiptHandle' => $id,
        ]);
    }

    private static function assertMessageExists(Result $message): array
    {
        self::assertArrayHasKey('Messages', $message);
        self::assertIsArray($message['Messages']);
        self::assertCount(1, $message['Messages']);
        self::assertIsArray($message['Messages'][0]);

        return $message['Messages'][0];
    }
}
