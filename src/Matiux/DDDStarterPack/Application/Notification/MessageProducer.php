<?php

namespace DDDStarterPack\Application\Notification;

interface MessageProducer
{
    public function open(string $exchangeName);

    public function send(
        string $exchangeName,
        string $notificationBodyMessage,
        string $notificationType,
        int $notificationId,
        \DateTimeInterface $notificationOccurredOn
    ): MessageProducerResponse;

    public function sendBatch(\ArrayObject $messages): MessageProducerResponse;

    public function close($exchangeName);
}
