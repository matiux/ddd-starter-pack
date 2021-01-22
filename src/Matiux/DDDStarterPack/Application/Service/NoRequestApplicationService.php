<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Service;

use DDDStarterPack\Domain\Service\NoRequestService;

/**
 * @template O
 *
 * @extends NoRequestService<O>
 */
interface NoRequestApplicationService extends NoRequestService
{
}
