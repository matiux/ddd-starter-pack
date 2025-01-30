<?php

declare(strict_types=1);

namespace DDDStarterPack\Type;

class DateTimeRFC extends \DateTimeImmutable
{
    /**
     * Based on DateTimeInterface::RFC3339_EXTENDED = Y-m-d\TH:i:s.vP
     * v = Milliseconds
     * u = Microseconds.
     */
    public const FORMAT = 'Y-m-d\TH:i:s.uP';
    public const NO_TZ_FORMAT = 'Y-m-d H:i:s.u';

    final public function __construct(string $datetime = 'now', null|\DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);
    }

    public static function UTC(): static
    {
        return new static(timezone: new \DateTimeZone('UTC'));
    }

    /**
     * @deprecated use DateTimeRFC::from() instead
     */
    public static function createFrom(string $dateTime, null|\DateTimeZone $timezone = null): static
    {
        return self::from($dateTime, $timezone);
    }

    public static function from(string $dateTime, null|\DateTimeZone $timezone = null): static
    {
        if (!$date = static::createFromFormat(self::FORMAT, $dateTime, $timezone)) {
            throw new \InvalidArgumentException(sprintf('Data non valida: %s', $dateTime));
        }

        return new static($date->format(self::FORMAT), $timezone);
    }

    public static function UTCfrom(string $dateTime): static
    {
        if (!$date = static::createFromFormat(self::FORMAT, $dateTime)) {
            throw new \InvalidArgumentException(sprintf('Data non valida: %s', $dateTime));
        }

        $tz = new \DateTimeZone('UTC');

        $date = $date->setTimezone($tz);

        return new static($date->format(self::FORMAT), $tz);
    }

    public function value(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return $this->format(self::FORMAT);
    }
}
