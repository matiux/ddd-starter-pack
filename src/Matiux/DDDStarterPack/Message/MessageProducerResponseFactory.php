<?php

declare(strict_types=1);

namespace DDDStarterPack\Message;

/**
 * @template T
 */
interface MessageProducerResponseFactory
{
    /**
     * @param int $sentMessages
     * @param T   $originalResponse
     *
     * @return MessageProducerResponse
     */
    public function build(int $sentMessages, $originalResponse): MessageProducerResponse;
}
