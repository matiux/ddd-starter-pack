<?php

namespace DddStarterPack\Application\Notification;

interface Message
{
    public function getExchangeName(): string;

    public function getNotificationBodyMessagee(): string;

    public function getNotificationType(): string;

    public function getNotificationId(): int;

    public function getNotificationOccuredOn(): \DateTimeInterface;
}
