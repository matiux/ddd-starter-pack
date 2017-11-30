<?php

namespace Tests\DddStarterPack\Application\Notification;

class QueueMock
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
