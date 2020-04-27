<?php

namespace Tests\Tool;

use Aws\Credentials\Credentials;
use Aws\Result;
use Aws\Sqs\SqsClient;
use DDDStarterPack\Application\Util\EnvVarUtil;

trait SqsRawClient
{
    private $client = null;
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
                'region' => EnvVarUtil::get('AWS_REGION', 'us-east-1'),
                'debug' => false,
            ];

            $this->client = new SqsClient($args + $this->createCredentials());

            $this->setUrl();
        }

        return $this->client;
    }

    private function createCredentials(): array
    {
        if (!empty(EnvVarUtil::get('AWS_ACCESS_KEY_ID')) && !empty(EnvVarUtil::get('AWS_SECRET_ACCESS_KEY'))) {

            $credentials = new Credentials(EnvVarUtil::get('AWS_ACCESS_KEY_ID'), EnvVarUtil::get('AWS_SECRET_ACCESS_KEY'));
            return ['credentials' => $credentials];
        }

        return [];
    }

    private function setUrl(): void
    {
        $url = $this->getClient()->getQueueUrl([
            'QueueName' => 'connettore-pvp-test'
        ]);

        $this->url = $url['QueueUrl'];
    }

    protected function purgeSqsQueue()
    {
        $fetched = 0;

        while (true) {

            $messages = $this->pullFromQueue(10)->get('Messages');

            if (!$messages) {
                break;
            }

            $fetched += count($messages);

            foreach ($messages as $message) {

                if ($message) {

                    $this->client->deleteMessage(array(
                        'QueueUrl' => $this->url,
                        'ReceiptHandle' => $message['ReceiptHandle']
                    ));
                }
            }

        }
    }

    private function pullFromQueue(int $amountOfMessagesToFetch = 1): Result
    {
        $options = [
            'QueueUrl' => $this->url,
            'MaxNumberOfMessages' => $amountOfMessagesToFetch,
            'WaitTimeSeconds' => 2
        ];

        return $this->getClient()->receiveMessage($options);
    }

    private function deleteMessage(string $id): void
    {
        $this->getClient()->deleteMessage([
            'QueueUrl' => $this->url,
            'ReceiptHandle' => $id
        ]);
    }
}
