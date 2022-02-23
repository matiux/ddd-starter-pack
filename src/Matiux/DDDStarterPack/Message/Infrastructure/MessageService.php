<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure;

interface MessageService
{
    public function open(string $exchangeName = ''): void;

    public function close(string $exchangeName = ''): void;
}
