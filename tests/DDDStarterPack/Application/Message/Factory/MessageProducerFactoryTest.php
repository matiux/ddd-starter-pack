<?php

namespace Tests\DDDStarterPack\Application\Message\Factory;

use DDDStarterPack\Application\Message\Factory\MessageProducerFactory;
use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageProducer;
use PHPUnit\Framework\TestCase;
use Tests\Tool\SqsRawClient;

class MessageProducerFactoryTest extends TestCase
{
    use SqsRawClient;

    /**
     * @test
     * @group factory
     * @group sqs
     */
    public function obtain_sqs_message_producer()
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withRegion('eu-west-1')
            ->withAccessKey(getenv('AWS_ACCESS_KEY_ID'))
            ->withSecretKey(getenv('AWS_SECRET_ACCESS_KEY'))
            ->withQueue($this->getQueueUrl())
            ->build();

        $factory = MessageProducerFactory::create();
        $sqsMessageProducer = $factory->obtainProducer($configuration);

        $this->assertInstanceOf(SQSMessageProducer::class, $sqsMessageProducer);
    }
}
