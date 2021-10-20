<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Infrastructure\AWS;

use DateTimeImmutable;
use DDDStarterPack\Message\Infrastructure\AWS\AWSMessageFactory;
use PHPUnit\Framework\TestCase;

class AWSMessageFactoryTest extends TestCase
{
    /**
     * @test
     * @group sqs
     */
    public function factory_should_create_sqs_message(): void
    {
        $SQSmessageFactory = new AWSMessageFactory();

        $message = $SQSmessageFactory->build(
            body: '',
            type: 'MyType',
            occurredAt: new DateTimeImmutable(),
        );

        self::assertSame('MyType', $message->type());
    }
}
