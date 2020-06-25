<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Service;

/**
 * Interface Service.
 *
 * @extends Service<null>
 */
interface NoRequestApplicationService extends Service
{
    /**
     * @param null $request
     *
     * @return mixed
     */
    public function execute($request = null);
}
