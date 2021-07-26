<?php

declare(strict_types=1);

namespace Tests\Tool;

use Aws\Credentials\Credentials;
use Aws\Result;
use Aws\Sqs\SqsClient;
use DDDStarterPack\Application\Util\EnvVarUtil;

/**
 * Trait SqsRawClient.
 *
 * @psalm-suppress MissingConstructor
 */
trait SqsRawClient
{
    /** @var null|SqsClient */
    private $client;

    /** @var string */
    private $url = '';

    public function getQueueUrl(): string
    {
        $this->getClient();

        return $this->url;
    }

    protected function getClient(): SqsClient
    {
        if (!$this->client) {
            $args = [
                'version' => 'latest',
                'region' => EnvVarUtil::get('AWS_DEFAULT_REGION', 'us-east-1'),
                'debug' => false,
            ];

            $this->client = new SqsClient($args + $this->createCredentials());

            $this->setUrl();
        }

        return $this->client;
    }

    protected function purgeSqsQueue(): void
    {
        $_fetched = 0;

        while (true) {
            /** @var list<array{MessageId: string, ReceiptHandle: string, MD5OfBody: string, Body: string }> $messages */
            $messages = $this->pullFromQueue(10)->get('Messages');

            if (!$messages) {
                break;
            }

            $_fetched += count($messages);

            foreach ($messages as $message) {
                if ($message) {
                    $this->getClient()->deleteMessage([
                        'QueueUrl' => $this->url,
                        'ReceiptHandle' => $message['ReceiptHandle'],
                    ]);
                }
            }
        }
    }

    private function createCredentials(): array
    {
        $accessKey = EnvVarUtil::get('AWS_ACCESS_KEY_ID');
        $secretKey = EnvVarUtil::get('AWS_SECRET_ACCESS_KEY');

        if (!empty($accessKey) && !empty($secretKey)) {
            $credentials = new Credentials($accessKey, $secretKey);

            return ['credentials' => $credentials];
        }

        return [];
    }

    private function setUrl(): void
    {
        /** @var array<string,string> $url */
        $url = $this->getClient()->getQueueUrl([
            'QueueName' => EnvVarUtil::get('AWS_SQS_QUEUE_NAME'),
        ]);

        $this->url = $url['QueueUrl'];
    }

    private function pullFromQueue(int $amountOfMessagesToFetch = 1): Result
    {
        $options = [
            'QueueUrl' => $this->url,
            'MaxNumberOfMessages' => $amountOfMessagesToFetch,
            'WaitTimeSeconds' => 2,
        ];

        return $this->getClient()->receiveMessage($options);
    }

    private function deleteMessage(string $id): void
    {
        $this->getClient()->deleteMessage([
            'QueueUrl' => $this->url,
            'ReceiptHandle' => $id,
        ]);
    }
}
