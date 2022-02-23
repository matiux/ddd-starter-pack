<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Infrastructure\Driver\AWS\SNS\Configuration;

use DDDStarterPack\Message\Infrastructure\Driver\AWS\SNS\Configuration\SNSConfigurationBuilder;
use PHPUnit\Framework\TestCase;

class SNSConfigurationBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function create_sns_configuration(): void
    {
        $configuration = SNSConfigurationBuilder::create()
            ->withAccessKey('access_key')
            ->withSecretKey('secret_key')
            ->withTopicArn('sns_topic_arn')
            ->withRegion('ireland')
            ->build();

        self::assertSame('SNS', $configuration->getDriverName());

        $params = [
            'access_key' => 'access_key',
            'secret_key' => 'secret_key',
            'sns_topic_arn' => 'sns_topic_arn',
            'region' => 'ireland',
        ];
        self::assertSame($params, $configuration->getParams());
    }
}
