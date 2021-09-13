<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Application\Message\Factory;

use DDDStarterPack\Application\Message\Factory\MessageConsumerFactory;
use DDDStarterPack\Application\Util\EnvVarUtil;
use DDDStarterPack\Infrastructure\Application\Message\AWS\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Infrastructure\Application\Message\AWS\SQS\SQSMessageConsumer;
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
