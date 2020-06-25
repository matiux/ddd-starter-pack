<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

/**
 * Interface MessageProducerResponse.
 */
interface MessageProducerResponse
{
    public function isSuccess(): bool;

    public function sentMessages(): int;

    /** @return mixed */
    public function originalResponse();

    /** @return mixed */
    public function body();

    /** @return mixed */
    public function sentMessageId();
}
