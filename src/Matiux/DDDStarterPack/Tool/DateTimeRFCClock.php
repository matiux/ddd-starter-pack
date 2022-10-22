<?php

declare(strict_types=1);

namespace DDDStarterPack\Tool;

use DDDStarterPack\Type\DateTimeRFC;

class DateTimeRFCClock implements Clock
{
    public function getCurrentTime(): DateTimeRFC
    {
        return new DateTimeRFC();
    }
}
