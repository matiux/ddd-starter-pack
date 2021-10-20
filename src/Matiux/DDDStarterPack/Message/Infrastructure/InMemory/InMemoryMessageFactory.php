<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\InMemory;

use DateTimeImmutable;
use DDDStarterPack\Message\Application\MessageFactory;
use Webmozart\Assert\Assert;

/**
 * @implements MessageFactory<InMemoryMessage>
 */
class InMemoryMessageFactory implements MessageFactory
{
    public function build(string $body, string $exchangeName = null, DateTimeImmutable $occurredAt = null, string $type = null, string $id = null)
    {
        Assert::notNull($exchangeName);
        Assert::notNull($id);
        Assert::notNull($type);
        Assert::notNull($occurredAt);

        return new InMemoryMessage($exchangeName, $id, $body, $type, $occurredAt);
    }
}
