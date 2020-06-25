<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Infrastructure\Application\Message\SQS;

use DateTimeImmutable;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageFactory;
use PHPUnit\Framework\TestCase;

class SQSMessageFactoryTest extends TestCase
{
    /**
     * @test
     * @group sqs
     */
    public function factory_should_create_sqs_message(): void
    {
        $SQSmessageFactory = new SQSMessageFactory();

        $message = $SQSmessageFactory->build('', '', new DateTimeImmutable(), 'MyType', '28');

        self::assertSame('MyType', $message->type());
    }
}
