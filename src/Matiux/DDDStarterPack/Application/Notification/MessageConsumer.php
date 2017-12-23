<?php

namespace DDDStarterPack\Application\Notification;

interface MessageConsumer
{
    public function open(string $exchangeName);

    public function receiveMessage(int $numberOfMessages = 1);

    public function close($exchangeName);
}
