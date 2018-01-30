<?php

namespace DDDStarterPack\Infrastructure\Application\Notification\InMemory;

use DDDStarterPack\Application\Notification\Message;
use DDDStarterPack\Application\Notification\NotificationMessageFactory;

class InMemoryNotificationMessageFactory implements NotificationMessageFactory
{
    public function build(string $exchangeName, int $notificationId, string $notificationBodyMessage, string $notificationType, \DateTimeInterface $notificationOccurredOn): Message
    {
        return new InMemoryMessage($exchangeName, $notificationId, $notificationBodyMessage, $notificationType, $notificationOccurredOn);
    }
}
