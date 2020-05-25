<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\InMemory;

use DDDStarterPack\Application\Message\Message;
use InvalidArgumentException;

class InMemoryMessageQueue
{
    /** @var array<array-key, Message[]> */
    private $messages = [];

    public function __construct()
    {
        $this->messages['default'] = [];
    }

    public function popMessage(string $exchangeName = 'default'): ?Message
    {
        if (!array_key_exists($exchangeName, $this->messages)) {
            throw new InvalidArgumentException("Queue '{$exchangeName}' doesn't exists");
        }

        return array_pop($this->messages[$exchangeName]);
    }

    public function appendMessage(Message $message, string $exchangeName = 'default'): void
    {
        if (!array_key_exists($exchangeName, $this->messages)) {
            $this->messages[$exchangeName] = [];
        }

        array_unshift($this->messages[$exchangeName], $message);
    }

    public function count(string $exchangeName = 'default'): int
    {
        if (array_key_exists($exchangeName, $this->messages)) {
            return count($this->messages[$exchangeName]);
        }

        throw new InvalidArgumentException("Queue '{$exchangeName}' doesn't exists");
    }
}
