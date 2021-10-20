<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\AWS\SQS;

use Aws\Sqs\SqsClient;
use DDDStarterPack\Application\Message\Configuration\Configuration;
use DDDStarterPack\Application\Message\Configuration\ConfigurationValidator;
use DDDStarterPack\Infrastructure\Application\Message\AWS\SQS\Configuration\SQSConfiguration;
use DDDStarterPack\Infrastructure\Application\Message\AWS\SQS\Configuration\SQSConfigurationValidator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Webmozart\Assert\Assert;

trait SQSBasicService
{
    private SQSConfiguration $configuration;
    private null|string $queueUrl = null;
    private null|SqsClient $client = null;

    protected function defaultsParams(): array
    {
        return $this->customDefaultsParams() + ['queue_url' => null];
    }

    protected function obtainConfigurationValidator(): ConfigurationValidator
    {
        return new SQSConfigurationValidator();
    }

    protected function setSpecificConfiguration(Configuration $configuration): void
    {
        /** @var string[] $params */
        $params = $configuration->getParams();

        $this->configuration = new SQSConfiguration(
            $params['region'],
            $params['access_key'] ?? null,
            $params['secret_key'] ?? null,
            $params['queue_url'] ?? null,
        );
    }

    protected function getClient(): SqsClient
    {
        if (!isset($this->client)) {
            $args = [
                'version' => 'latest',
                'region' => $this->configuration->region(),
                'debug' => false,
            ];

            $this->client = new SqsClient($args + $this->createCredentials());
        }

        return $this->client;
    }

    private function getQueueUrlFromConfig(): string
    {
        if (!isset($this->queueUrl)) {
            $this->setQueueUrlOrFail();
        }

        Assert::notNull($this->queueUrl, 'Coda SQS non può essere null');

        return $this->queueUrl;
    }

    private function setQueueUrlOrFail(): void
    {
        $this->queueUrl = $this->configuration->queueUrl();

        if (!$this->queueUrl || 0 === strlen(trim($this->queueUrl))) {
            throw new InvalidConfigurationException('Queue url missing');
        }
    }
}
