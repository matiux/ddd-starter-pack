<?php

namespace DDDStarterPack\Application\Notification;

interface MessageProducerResponse
{
    public function getSentMessages(): int;

    public function getOriginalResponse();
}
