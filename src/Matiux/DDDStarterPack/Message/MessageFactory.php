<?php

declare(strict_types=1);

namespace DDDStarterPack\Message;

/**
 * @template T of Message
 */
interface MessageFactory
{
    /**
     * @param string                  $body
     * @param null|\DateTimeImmutable $occurredAt
     * @param null|string             $type
     * @param null|string             $id
     * @param array                   $extra
     * @param null|string             $exchangeName
     *
     * @return T
     */
    public function build(
        string $body,
        \DateTimeImmutable|null $occurredAt = null,
        string|null $type = null,
        string|null $id = null,
        array $extra = [],
        string|null $exchangeName = null,
    );
}
