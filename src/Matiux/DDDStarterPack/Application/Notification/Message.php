<?php

namespace DDDStarterPack\Application\Notification;

interface Message
{
    public function getExchangeName(): string;

    public function getNotificationBodyMessage(): string;

    public function getNotificationType(): string;

    public function getNotificationId(): int;

    public function getNotificationOccuredOn(): \DateTimeInterface;
}
