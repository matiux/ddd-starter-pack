<?php

declare(strict_types=1);

namespace DDDStarterPack\Tool;

use DDDStarterPack\Type\DateTimeRFC;

class DateTimeRFCClock implements Clock
{
    public function getCurrentTime(\DateTimeZone $dateTimeZone = null): DateTimeRFC
    {
        return new DateTimeRFC(timezone: $dateTimeZone);
    }

    public function getCurrentTimeUTC(): DateTimeRFC
    {
        return DateTimeRFC::nowUTC();
    }
}
