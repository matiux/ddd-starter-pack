<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Domain\Response;

/**
 * @template B of mixed
 */
interface ServiceResponse
{
    public function isSuccess(): bool;

    /**
     * @return B
     */
    public function body();

    public function code(): int;
}
