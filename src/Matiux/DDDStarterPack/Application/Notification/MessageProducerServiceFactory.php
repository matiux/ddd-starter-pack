<?php

namespace DDDStarterPack\Application\Notification;

interface MessageProducerServiceFactory
{
    public function build(int $sentMessages, ?$originalResponse = null): MessageProducerResponse;
}
