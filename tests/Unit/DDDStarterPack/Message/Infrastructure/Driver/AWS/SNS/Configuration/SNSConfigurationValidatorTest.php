<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Infrastructure\Driver\AWS\SNS\Configuration;

use DDDStarterPack\Message\Infrastructure\Driver\AWS\SNS\Configuration\SNSConfigurationBuilder;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\SNS\Configuration\SNSConfigurationValidator;
use PHPUnit\Framework\TestCase;

class SNSConfigurationValidatorTest extends TestCase
{
    private SNSConfigurationValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new SNSConfigurationValidator();
    }

    /**
     * @test
     *
     * @group sns
     * @group configuration
     * @group validation
     */
    public function sns_configuration_cannot_bet_empty(): void
    {
        $configuration = SNSConfigurationBuilder::create()->build();

        $valid = $this->validator->validate($configuration);

        $this->assertFalse($valid);
    }

    /**
     * @test
     *
     * @group sqs
     * @group configuration
     * @group validation
     */
    public function sns_configuration_params_cannot_bet_empty(): void
    {
        $configuration = SNSConfigurationBuilder::create()
            ->withRegion('')
            ->withTopicArn('')
            ->withSecretKey('')
            ->withAccessKey('')
            ->build();

        $valid = $this->validator->validate($configuration);

        $this->assertFalse($valid);
    }

    /**
     * @test
     *
     * @group sqs
     * @group configuration
     * @group validation
     */
    public function valid_sns_configuration(): void
    {
        $configuration = SNSConfigurationBuilder::create()
            ->withRegion('foo')
            ->withTopicArn('foo')
            ->withSecretKey('foo')
            ->withAccessKey('foo')
            ->build();

        $valid = $this->validator->validate($configuration);

        $this->assertTrue($valid);
    }
}
