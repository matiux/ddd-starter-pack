<?php

namespace DddStarterPack\Application\Notification;

interface MessageConsumer
{
    public function open(string $exchangeName);

    public function receiveMessage();

    public function close($exchangeName);
}
