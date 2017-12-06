<?php

namespace DDDStarterPack\Infrastructure\Application\Notification\InMemory;

class InMemoryMessageQueue
{
    private $messages = [];

    public function __construct()
    {
        $this->messages['default'] = [];
    }

    public function popMessage(string $exchangeName = 'default'): ?string
    {
        if (array_key_exists($exchangeName, $this->messages)) {

            return array_pop($this->messages[$exchangeName]);
        }

        throw new \InvalidArgumentException("Queue '$exchangeName' doesn't exists");
    }

    public function appendMessage($message, string $exchangeName = 'default')
    {
        if (!array_key_exists($exchangeName, $this->messages)) {

            $this->messages[$exchangeName] = [];
        }

        array_unshift($this->messages[$exchangeName], $message);
    }

    public function count(string $exchangeName = 'default')
    {
        if (array_key_exists($exchangeName, $this->messages)) {

            return count($this->messages[$exchangeName]);
        }

        throw new \InvalidArgumentException("Queue '$exchangeName' doesn't exists");
    }
}
