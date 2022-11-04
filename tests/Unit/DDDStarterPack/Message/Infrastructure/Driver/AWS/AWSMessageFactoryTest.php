<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Infrastructure\Driver\AWS;

use DDDStarterPack\Message\Infrastructure\Driver\AWS\AWSMessageFactory;
use PHPUnit\Framework\TestCase;

class AWSMessageFactoryTest extends TestCase
{
    /**
     * @test
     *
     * @group sqs
     */
    public function factory_should_create_sqs_message(): void
    {
        $SQSmessageFactory = new AWSMessageFactory();

        $message = $SQSmessageFactory->build(
            body: '',
            occurredAt: new \DateTimeImmutable(),
            type: 'MyType',
        );

        self::assertSame('MyType', $message->type());
    }
}
