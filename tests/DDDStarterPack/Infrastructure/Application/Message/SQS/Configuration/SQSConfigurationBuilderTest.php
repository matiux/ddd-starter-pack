<?php

namespace Tests\DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration;

use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\SQSConfigurationBuilder;
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
            ->withQueue('queue_name')
            ->withRegion('ireland')
            ->build();

        self::assertSame('SQS', $configuration->getDriverName());

        $params = [
            'access_key' => 'access_key',
            'secret_key' => 'secret_key',
            'queue_name' => 'queue_name',
            'region' => 'ireland',
        ];
        self::assertSame($params, $configuration->getParams());
    }
}
