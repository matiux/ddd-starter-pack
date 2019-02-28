<?php

namespace DDDStarterPack\Application\Notification;

interface MessageService
{
    public function open(string $exchangeName = ''): void;

    public function close(string $exchangeName = ''): void;
}
