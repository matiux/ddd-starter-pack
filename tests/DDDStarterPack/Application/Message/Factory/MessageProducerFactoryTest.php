<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Application\Message\Factory;

use DDDStarterPack\Application\Message\Factory\MessageProducerFactory;
use DDDStarterPack\Application\Util\EnvVarUtil;
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
            ->withQueue($this->getQueueUrl())
            ->build();

        $factory = MessageProducerFactory::create();
        $sqsMessageProducer = $factory->obtainProducer($configuration);

        $this->assertInstanceOf(SQSMessageProducer::class, $sqsMessageProducer);
    }
}
