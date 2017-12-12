<?php

namespace DddStarterPack\Application\Notification;

interface MessageProducerResponse
{
    public function getSentMessages(): int;

    public function getInQueueMessages(): int;
}
