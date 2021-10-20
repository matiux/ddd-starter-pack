<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Application\Response;

use DDDStarterPack\Service\Domain\Response\BasicServiceResponse;
use DDDStarterPack\Service\Domain\Response\ServiceResponse;

/**
 * @template T
 * @extends BasicServiceResponse<T>
 */
abstract class BasicApplicationServiceResponse extends BasicServiceResponse implements ServiceResponse
{
}
