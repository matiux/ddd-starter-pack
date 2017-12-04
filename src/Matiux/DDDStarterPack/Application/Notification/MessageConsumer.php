<?php

namespace DDDStarterPack\Application\Notification;

interface MessageConsumer
{
    public function open(string $exchangeName);

    public function receiveMessage();

    public function close($exchangeName);
}
