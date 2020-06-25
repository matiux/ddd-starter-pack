<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\SQS;

use DateTimeImmutable;
use DDDStarterPack\Application\Message\MessageFactory;
use Webmozart\Assert\Assert;

/**
 * Class SQSMessageFactory.
 *
 * @implements MessageFactory<SQSMessage>
 */
class SQSMessageFactory implements MessageFactory
{
    /**
     * @param string                 $body
     * @param null|string            $exchangeName
     * @param null|DateTimeImmutable $occurredAt
     * @param null|string            $type
     * @param null|string            $id
     *
     * @return SQSMessage
     */
    public function build(string $body, string $exchangeName = null, DateTimeImmutable $occurredAt = null, string $type = null, string $id = null)
    {
        Assert::notNull($occurredAt);

        return new SQSMessage($body, $occurredAt, $type, $id);
    }
}
