<?php

namespace DDDStarterPack\Application\Notification;

interface MessageProducer
{
    public function open(string $exchangeName);

    public function send(
        string $exchangeName,
        string $notificationMessage,
        string $notificationType,
        int $notificationId,
        \DateTimeInterface $notificationOccurredOn
    ): MessageProducerResponse;

    public function close($exchangeName);
}
