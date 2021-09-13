<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\InMemory;

use DateTimeImmutable;
use DDDStarterPack\Application\Message\Message;

class InMemoryMessage implements Message
{
    private $exchangeName;
    private $notificationId;
    private $notificationBodyMessage;
    private $notificationType;
    private $occurredOn;

    public function __construct(string $exchangeName, string $notificationId, string $notificationBodyMessage, string $notificationType, DateTimeImmutable $occurredOn)
    {
        $this->exchangeName = $exchangeName;
        $this->notificationId = $notificationId;
        $this->notificationBodyMessage = $notificationBodyMessage;
        $this->notificationType = $notificationType;
        $this->occurredOn = $occurredOn;
    }

    public function exchangeName(): string
    {
        return $this->exchangeName;
    }

    public function body(): string
    {
        return $this->notificationBodyMessage;
    }

    public function type(): string
    {
        return $this->notificationType;
    }

    public function id(): mixed
    {
        return $this->notificationId;
    }

    public function occurredAt(): DateTimeImmutable|null
    {
        return $this->occurredOn;
    }
}
