<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\AWS\RawClient;

use Aws\Sns\SnsClient;
use DDDStarterPack\Util\EnvVarUtil;
use Webmozart\Assert\Assert;

trait SnsRawClient
{
    use AWSCredentials;

    private null|SnsClient $snsClient = null;
    private null|string $snsTopicArn = null;

    protected function getSnsTopicArn(): string
    {
        Assert::notNull($this->snsTopicArn, 'Topic ARN non può essere null');

        return $this->snsTopicArn;
    }

    protected function setSnsTopicArn(string $snsTopicArn = null): void
    {
        //  ?? EnvVarUtil::get('AWS_SNS_TOPIC_ARN');
        $this->snsTopicArn = $snsTopicArn;
    }

    protected function getSnsClient(null|string $snsTopicArn = null): SnsClient
    {
        if ($snsTopicArn) {
            $this->setSnsTopicArn($snsTopicArn);
        }

        if (!$this->snsClient) {
            $args = [
                'version' => 'latest',
                'region' => EnvVarUtil::get('AWS_DEFAULT_REGION'),
                'debug' => false,
            ];

            $this->snsClient = new SnsClient($args + $this->createCredentials());
        }

        return $this->snsClient;
    }
}
