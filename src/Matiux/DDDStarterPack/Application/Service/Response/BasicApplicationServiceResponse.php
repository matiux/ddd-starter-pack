<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Service\Response;

use DDDStarterPack\Domain\Service\Response\BasicServiceResponse;
use DDDStarterPack\Domain\Service\Response\ServiceResponse;

abstract class BasicApplicationServiceResponse extends BasicServiceResponse implements ServiceResponse
{
}
