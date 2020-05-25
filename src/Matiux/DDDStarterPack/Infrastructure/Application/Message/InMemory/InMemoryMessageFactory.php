<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\InMemory;

use DateTimeImmutable;
use DDDStarterPack\Application\Message\MessageFactory;
use Webmozart\Assert\Assert;

/**
 * Class InMemoryMessageFactory.
 *
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
