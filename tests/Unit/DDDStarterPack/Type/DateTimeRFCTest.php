<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Type;

use DDDStarterPack\Type\DateTimeRFC;
use PHPUnit\Framework\TestCase;

class DateTimeRFCTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_create_utc_date_time_from_different_timezone_date_time_string(): void
    {
        $now = new DateTimeRFC('2024-06-27T21:06:37.879786+02:00');
        $nowToUTC = DateTimeRFC::UTCfrom($now->value());

        self::assertSame('2024-06-27T21:06:37.879786+02:00', $now->value());
        self::assertSame('2024-06-27T19:06:37.879786+00:00', $nowToUTC->value());
    }

    /**
     * @test
     */
    public function it_should_create_utc_date_time(): void
    {
        $utc = DateTimeRFC::UTC();

        $timezone = $utc->getTimezone();
        self::assertInstanceOf(\DateTimeZone::class, $timezone);
        self::assertSame('UTC', $timezone->getName());
    }
}
