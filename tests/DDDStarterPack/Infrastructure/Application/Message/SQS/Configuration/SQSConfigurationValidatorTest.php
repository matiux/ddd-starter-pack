<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration;

use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\SQSConfigurationValidator;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class SQSConfigurationValidatorTest extends TestCase
{
    /** @var SQSConfigurationValidator */
    private $validator;

    protected function setUp(): void
    {
        $this->validator = new SQSConfigurationValidator();
    }

    /**
     * @test
     * @group sqs
     * @group configuration
     * @group validation
     */
    public function sqs_configuration_cannot_bet_empty(): void
    {
        $configuration = SQSConfigurationBuilder::create()->build();

        $valid = $this->validator->validate($configuration);

        $this->assertFalse($valid);
    }

    /**
     * @test
     * @group sqs
     * @group configuration
     * @group validation
     */
    public function s3_configuration_params_cannot_bet_empty(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withRegion('')
            ->withQueue('')
            ->withSecretKey('')
            ->withAccessKey('')
            ->build();

        $valid = $this->validator->validate($configuration);

        $this->assertFalse($valid);
    }

    /**
     * @test
     * @group sqs
     * @group configuration
     * @group validation
     */
    public function valid_sqs_configuration(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withRegion('foo')
            ->withQueue('foo')
            ->withSecretKey('foo')
            ->withAccessKey('foo')
            ->build();

        $valid = $this->validator->validate($configuration);

        $this->assertTrue($valid);
    }
}
