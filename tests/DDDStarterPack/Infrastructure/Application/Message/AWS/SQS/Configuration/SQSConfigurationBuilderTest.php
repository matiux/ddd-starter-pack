<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Infrastructure\Application\Message\AWS\SQS\Configuration;

use DDDStarterPack\Infrastructure\Application\Message\AWS\SQS\Configuration\SQSConfigurationBuilder;
use PHPUnit\Framework\TestCase;

class SQSConfigurationBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function create_sqs_message_configuration(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withAccessKey('access_key')
            ->withSecretKey('secret_key')
            ->withQueueUrl('queue_url')
            ->withRegion('ireland')
            ->build();

        self::assertSame('SQS', $configuration->getDriverName());

        $params = [
            'access_key' => 'access_key',
            'secret_key' => 'secret_key',
            'queue_url' => 'queue_url',
            'region' => 'ireland',
        ];
        self::assertSame($params, $configuration->getParams());
    }
}
