<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Infrastructure\Factory;

use DDDStarterPack\Message\Infrastructure\Driver\AWS\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\SQS\SQSMessageProducer;
use DDDStarterPack\Message\Infrastructure\Factory\MessageProducerFactory;
use DDDStarterPack\Tool\EnvVarUtil;
use PHPUnit\Framework\TestCase;

class MessageProducerFactoryTest extends TestCase
{
    /**
     * @test
     *
     * @group factory
     * @group sqs
     */
    public function obtain_sqs_message_producer(): void
    {
        $accessKey = EnvVarUtil::get('AWS_ACCESS_KEY_ID');
        $secretKey = EnvVarUtil::get('AWS_SECRET_ACCESS_KEY');

        self::assertNotEmpty($accessKey);
        self::assertNotEmpty($secretKey);

        $configuration = SQSConfigurationBuilder::create()
            ->withRegion('eu-west-1')
            ->withAccessKey($accessKey)
            ->withSecretKey($secretKey)
            ->withQueueUrl(EnvVarUtil::get('AWS_SQS_QUEUE_NAME'))
            ->build();

        $factory = MessageProducerFactory::create();
        $sqsMessageProducer = $factory->obtainProducer($configuration);

        $this->assertInstanceOf(SQSMessageProducer::class, $sqsMessageProducer);
    }
}
