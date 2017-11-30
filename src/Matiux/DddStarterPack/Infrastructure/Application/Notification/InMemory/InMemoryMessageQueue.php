<?php

namespace DddStarterPack\Infrastructure\Application\Notification\InMemory;

class InMemoryMessageQueue
{
    private $messages = [];

    public function popMessage()
    {
        return array_pop($this->messages);
    }

    public function appendMessage($message)
    {
        array_unshift($this->messages, $message);
    }

    public function count()
    {
        return count($this->messages);
    }
}
