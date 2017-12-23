<?php

namespace Tests\DddStarterPack\Infrastructure\Application\Notification;

use DDDStarterPack\Application\Notification\Message;

class InMemoryMessage implements Message
{
    private $exchangeName;
    private $notificationId;
    private $notificationBodyMessage;
    private $notificationType;
    private $occurredOn;

    public function __construct(string $exchangeName, int $notificationId, string $notificationBodyMessage, string $notificationType, \DateTimeInterface $occurredOn)
    {
        $this->exchangeName = $exchangeName;
        $this->notificationId = $notificationId;
        $this->notificationBodyMessage = $notificationBodyMessage;
        $this->notificationType = $notificationType;
        $this->occurredOn = $occurredOn;
    }

    public function getExchangeName(): string
    {
        return $this->exchangeName;
    }

    public function getNotificationBodyMessage(): string
    {
        return $this->notificationBodyMessage;
    }

    public function getNotificationType(): string
    {
        return $this->notificationType;
    }

    public function getNotificationId(): int
    {
        return $this->notificationId;
    }

    public function getNotificationOccurredOn(): \DateTimeInterface
    {
        return $this->occurredOn;
    }
}
