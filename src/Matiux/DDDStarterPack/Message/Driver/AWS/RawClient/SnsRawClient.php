<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\RawClient;

use Aws\Sns\SnsClient;
use DDDStarterPack\Tool\EnvVarUtil;
use Webmozart\Assert\Assert;

/**
 * @codeCoverageIgnore
 */
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

    protected function setSnsTopicArn(null|string $snsTopicArn = null): void
    {
        //  ?? EnvVarUtil::get('AWS_SNS_TOPIC_ARN');
        $this->snsTopicArn = $snsTopicArn;
    }

    protected function getSnsClient(null|string $snsTopicArn = null): SnsClient
    {
        if (!\is_null($snsTopicArn) && strlen($snsTopicArn) > 0) {
            $this->setSnsTopicArn($snsTopicArn);
        }

        if (!$this->snsClient) {
            $args = [
                'version' => 'latest',
                'region' => EnvVarUtil::get('AWS_DEFAULT_REGION'),
                'debug' => false,
                'retries' => 3,
            ];

            $this->snsClient = new SnsClient($args + $this->createCredentials());
        }

        return $this->snsClient;
    }
}
