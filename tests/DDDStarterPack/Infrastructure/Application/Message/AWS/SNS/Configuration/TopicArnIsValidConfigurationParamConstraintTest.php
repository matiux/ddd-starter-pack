<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Infrastructure\Application\Message\AWS\SNS\Configuration;

use DDDStarterPack\Application\Message\Configuration\Configuration;
use DDDStarterPack\Infrastructure\Application\Message\AWS\SNS\Configuration\SNSConfigurationBuilder;
use DDDStarterPack\Infrastructure\Application\Message\AWS\SNS\Configuration\TopicArnIsValidConfigurationParamConstraint;
use PHPUnit\Framework\TestCase;

class TopicArnIsValidConfigurationParamConstraintTest extends TestCase
{
    /**
     * @test
     */
    public function sns_topic_arn_must_be_valid(): void
    {
        $configuration = SNSConfigurationBuilder::create()
            ->withTopicArn('sns_topic_arn')
            ->build();

        $constraint = new TopicArnIsValidConfigurationParamConstraint();

        self::assertTrue($constraint->isSatisfiedBy($configuration));
    }

    /**
     * @test
     */
    public function sns_topic_arn_cannot_be_empty(): void
    {
        $configuration = SNSConfigurationBuilder::create()
            ->withTopicArn('')
            ->build();

        $constraint = new TopicArnIsValidConfigurationParamConstraint();

        self::assertFalse($constraint->isSatisfiedBy($configuration));
    }

    /**
     * @test
     */
    public function sns_topic_arn_must_be_exist(): void
    {
        $configuration = Configuration::withParams('Foo', []);

        $constraint = new TopicArnIsValidConfigurationParamConstraint();

        self::assertFalse($constraint->isSatisfiedBy($configuration));
    }
}
