<?php

namespace Tests\DDDStarterPack\Application\Message\Factory;

use DDDStarterPack\Application\Message\Factory\MessageConsumerFactory;
use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageConsumer;
use PHPUnit\Framework\TestCase;
use Tests\Tool\SqsRawClient;

class MessageConsumerFactoryTest extends TestCase
{
    use SqsRawClient;

    /**
     * @test
     * @group factory
     * @group sqs
     */
    public function obtain_sqs_message_consumer()
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withRegion('eu-west-1')
            ->withAccessKey(getenv('AWS_ACCESS_KEY_ID'))
            ->withSecretKey(getenv('AWS_SECRET_ACCESS_KEY'))
            ->withQueue($this->getQueueUrl())
            ->build();

        $factory = MessageConsumerFactory::create();
        $sqsMessageConsumer = $factory->obtainConsumer($configuration);

        $this->assertInstanceOf(SQSMessageConsumer::class, $sqsMessageConsumer);
    }
}
