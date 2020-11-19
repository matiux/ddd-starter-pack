<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\SQS;

use Aws\Credentials\Credentials;
use Aws\Sqs\SqsClient;
use BadMethodCallException;
use DDDStarterPack\Application\Message\BasicMessageService;
use DDDStarterPack\Application\Message\Configuration\Configuration;
use DDDStarterPack\Application\Message\Configuration\ConfigurationValidator;
use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\SQSConfiguration;
use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\SQSConfigurationValidator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

abstract class SQSMessageService extends BasicMessageService
{
    const SQS_MAX_MESSAGES = 10;
    const NAME = 'SQS';

    /** @var null|SqsClient */
    private $client;

    /** @var null|string */
    private $queue;

    /** @var SQSConfiguration */
    private $SQSConfiguration;

    public function open(string $exchangeName = ''): void
    {
        throw new BadMethodCallException('Unnecessary call');
    }

    public function close(string $exchangeName = ''): void
    {
        throw new BadMethodCallException('Unnecessary call');
    }

    protected function getClient(): SqsClient
    {
        if (!$this->client) {
            $this->setQueueUrlOrFail();

            $args = [
                'version' => 'latest',
                'region' => $this->SQSConfiguration->region(),
                'debug' => false,
            ];

            $this->client = new SqsClient($args + $this->createCredentials());
        }

        return $this->client;
    }

    private function setQueueUrlOrFail(): void
    {
        $this->queue = $this->SQSConfiguration->queue();

        if (!$this->queue || 0 === strlen(trim($this->queue))) {
            throw new InvalidConfigurationException('Queue url missing');
        }
    }

    private function createCredentials(): array
    {
        if (!empty($this->SQSConfiguration->accessKey()) && !empty($this->SQSConfiguration->secretKey())) {
            $credentials = new Credentials($this->SQSConfiguration->accessKey(), $this->SQSConfiguration->secretKey());

            return ['credentials' => $credentials];
        }

        return [];
    }

    protected function getQueueUrl(): string
    {
        if (!isset($this->queue)) {
            $this->setQueueUrlOrFail();
        }

        return $this->queue;
    }

    protected function specificDriverName(): string
    {
        return self::NAME;
    }

    protected function requiredParams(): array
    {
        return ['region', 'queue_name'];
    }

    protected function defaultsParams(): array
    {
        return [
            'debug' => false,
            'version' => 'latest',
            'access_key' => '',
            'secret_key' => '',
        ];
    }

    protected function obtainConfigurationValidator(): ConfigurationValidator
    {
        return new SQSConfigurationValidator();
    }

    protected function setSpecificConfiguration(Configuration $configuration): void
    {
        $params = $configuration->getParams();

        $accessKey = $params['access_key'] ?? '';
        $secretKey = $params['secret_key'] ?? '';

        $this->SQSConfiguration = new SQSConfiguration(
            (string) $params['queue_name'],
            (string) $params['region'],
            (string) $accessKey,
            (string) $secretKey
        );
    }
}
