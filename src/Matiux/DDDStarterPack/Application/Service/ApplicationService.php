<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Service;

use DDDStarterPack\Domain\Service\Service;

/**
 * @template I
 * @template O
 *
 * @extends Service<I, O>
 */
interface ApplicationService extends Service
{
}
