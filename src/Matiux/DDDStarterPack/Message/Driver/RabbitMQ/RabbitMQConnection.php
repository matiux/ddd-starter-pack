<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\RabbitMQ;

class RabbitMQConnection
{
    private $host;
    private $port;
    private $user;
    private $password;

    public function __construct(string $host, int $port, string $user, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
    }

    public function host(): string
    {
        return $this->host;
    }

    public function port(): int
    {
        return $this->port;
    }

    public function user(): string
    {
        return $this->user;
    }

    public function password(): string
    {
        return $this->password;
    }
}
