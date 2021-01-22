<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Service;

/**
 * @template I
 * @template O
 */
interface Service
{
    /**
     * @param I $request
     *
     * @return O
     */
    public function execute($request);
}
