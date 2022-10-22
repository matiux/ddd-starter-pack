<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Infrastructure\Factory;

use DDDStarterPack\Message\Infrastructure\Driver\AWS\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\SQS\SQSMessageConsumer;
use DDDStarterPack\Message\Infrastructure\Factory\MessageConsumerFactory;
use DDDStarterPack\Tool\EnvVarUtil;
use PHPUnit\Framework\TestCase;

class MessageConsumerFactoryTest extends TestCase
{
    /**
     * @test
     * @group factory
     * @group sqs
     */
    public function obtain_sqs_message_consumer(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withRegion('eu-west-1')
            ->withQueueUrl(EnvVarUtil::get('AWS_SQS_QUEUE_NAME'))
            ->build();

        $factory = MessageConsumerFactory::create();
        $sqsMessageConsumer = $factory->obtainConsumer($configuration);

        $this->assertInstanceOf(SQSMessageConsumer::class, $sqsMessageConsumer);
    }
}
