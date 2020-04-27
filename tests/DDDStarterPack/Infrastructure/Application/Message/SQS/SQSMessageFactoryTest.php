<?php

namespace Tests\DDDStarterPack\Infrastructure\Application\Message\SQS;

use DateTimeImmutable;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessage;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageFactory;
use PHPUnit\Framework\TestCase;

class SQSMessageFactoryTest extends TestCase
{
    /**
     * @test
     * @group sqs
     */
    public function factory_should_create_sqs_message()
    {
        $SQSmessageFactory = new SQSMessageFactory();

        $message = $SQSmessageFactory->build('', '', new DateTimeImmutable(), 'MyType', 28);

        $this->assertInstanceOf(SQSMessage::class, $message);
    }
}
