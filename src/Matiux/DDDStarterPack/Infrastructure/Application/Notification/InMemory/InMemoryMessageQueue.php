<?php

namespace DDDStarterPack\Infrastructure\Application\Notification\InMemory;

class InMemoryMessageQueue
{
    private $messages = [];

    public function __construct()
    {
        $this->messages['default'] = [];
    }

    public function popMessage(string $queue = 'default'): ?string
    {
        if (array_key_exists($queue, $this->messages)) {

            return array_pop($this->messages[$queue]);
        }

        throw new \InvalidArgumentException("Queue '$queue' doesn't exists");
    }

    public function appendMessage($message, string $queue = 'default')
    {
        if (array_key_exists($queue, $this->messages)) {

            array_unshift($this->messages[$queue], $message);
        }

        throw new \InvalidArgumentException("Queue '$queue' doesn't exists");
    }

    public function count(string $queue = 'default')
    {
        if (array_key_exists($queue, $this->messages)) {

            return count($this->messages[$queue]);
        }

        throw new \InvalidArgumentException("Queue '$queue' doesn't exists");
    }
}
