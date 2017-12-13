<?php

namespace DDDStarterPack\Application\Notification;

interface MessageProducerResponceFactory
{
    public function build(int $sentMessages, ?$originalResponse = null): MessageProducerResponse;
}
