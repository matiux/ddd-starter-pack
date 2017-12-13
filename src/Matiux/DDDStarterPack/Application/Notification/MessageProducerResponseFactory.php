<?php

namespace DDDStarterPack\Application\Notification;

interface MessageProducerResponseFactory
{
    public function build(int $sentMessages, $originalResponse = null): MessageProducerResponse;
}
