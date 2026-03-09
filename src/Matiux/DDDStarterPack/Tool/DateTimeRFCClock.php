<?php

declare(strict_types=1);

namespace DDDStarterPack\Tool;

use DDDStarterPack\Type\DateTimeRFC;

class DateTimeRFCClock implements Clock
{
    #[\Override]
    public function getCurrentTime(\DateTimeZone|null $dateTimeZone = null): DateTimeRFC
    {
        return new DateTimeRFC(timezone: $dateTimeZone);
    }

    #[\Override]
    public function getCurrentTimeUTC(): DateTimeRFC
    {
        return DateTimeRFC::UTC();
    }
}
