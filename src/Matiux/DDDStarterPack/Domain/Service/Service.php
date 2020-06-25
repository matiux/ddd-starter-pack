<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Service;

/**
 * Interface Service.
 *
 * @template T
 */
interface Service
{
    /**
     * @param T $request
     *
     * @return mixed
     */
    public function execute($request);
}
