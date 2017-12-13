<?php

namespace Tests\DddStarterPack\Infrastructure\Application\Notification;

use DDDStarterPack\Application\Notification\Message;

class InMemoryMessage implements Message
{
    private $exchangeName;
    private $notificationId;
    private $notificationBodyMessage;
    private $notificationType;
    private $occuredOn;

    public function __construct(string $exchangeName, int $notificationId, string $notificationBodyMessage, string $notificationType, \DateTimeInterface $occuredOn)
    {
        $this->exchangeName = $exchangeName;
        $this->notificationId = $notificationId;
        $this->notificationBodyMessage = $notificationBodyMessage;
        $this->notificationType = $notificationType;
        $this->occuredOn = $occuredOn;
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

    public function getNotificationOccuredOn(): \DateTimeInterface
    {
        return $this->occuredOn;
    }
}
