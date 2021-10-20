<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Application;

use DDDStarterPack\Service\Domain\NoRequestService;

/**
 * @template O
 *
 * @extends NoRequestService<O>
 */
interface NoRequestApplicationService extends NoRequestService
{
}
