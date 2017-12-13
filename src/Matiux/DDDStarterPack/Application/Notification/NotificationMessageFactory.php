<?php

namespace DDDStarterPack\Application\Notification;

interface NotificationMessageFactory
{
    public function build(string $exchangeName, int $notificationId, string $notificationBodyMessage, string $notificationType, \DateTimeInterface $notificationOccuredOn): Message;
}
