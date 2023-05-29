<?php

declare(strict_types=1);

namespace DDDStarterPack\Service;

/**
 * @template O
 *
 * @extends Service<null, O>
 */
interface NoRequestService extends Service
{
    /**
     * @param null $request
     *
     * @return O
     */
    public function execute($request = null);
}
