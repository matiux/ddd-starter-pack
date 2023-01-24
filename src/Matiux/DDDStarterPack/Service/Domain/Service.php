<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Domain;

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
