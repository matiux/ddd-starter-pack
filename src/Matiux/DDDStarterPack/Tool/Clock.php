<?php

declare(strict_types=1);

namespace DDDStarterPack\Tool;

use DDDStarterPack\Type\DateTimeRFC;

interface Clock
{
    public function getCurrentTime(\DateTimeZone $dateTimeZone = null): DateTimeRFC;

    public function getUTCCurrentTime(): DateTimeRFC;
}
